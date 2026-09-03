# Tuoora — App/Backend Gap-Closure Roadmap

Locked-in decisions and task list for bringing the Flutter app (`tuoora`) to
parity with the backend (`fee_easy` / `tuoora-backend`), plus the new modules
(Exams, Timetable, White-label, Birthdays) needed to close remaining gaps.

Status: **All 7 tasks code-complete as of 2026-09-02** (Payments cleanup, Exams, Timetable,
White-label, Birthdays, Homework submission, Student ID card). Only Exams was driven end-to-end
on a real device this session; everything after it is verified via `flutter analyze` +
live backend curl tests against real seeded data, not an on-device UI pass (standing
instruction partway through the session to hold off on device testing for the rest of the
roadmap). See each section below for exactly what was and wasn't verified.

---

## Decisions (defaults applied)

| # | Question | Answer |
|---|----------|--------|
| 1c | Deprecate offline-renewal/IAP-verify backend routes? | **Leave untouched** — web/admin still uses them |
| 1 | "Manage on web" subscription view? | **Repoint** to a simple "manage on web" info/contact link instead of removing outright |
| 2b | Exams: top-level or nested under Batch? | **Top-level module** — consistent with Timetable/Homework |
| 2c | Exams: extra fields? | **Add max marks + exam type**; skip weightage (not needed without GPA calc) |
| 3 | Timetable slots reference Staff (Teachers deferred) | **Plain-text staff name label** only — no Teachers module, no profile links |
| 4 | White-label: runtime-fetch vs baked | **Split**, refined after learning the real deployment model (institute self-purchases the add-on from their panel → ops manually confirms logo/name → ops manually uploads the build to Play Console — no shared reskinning APK, no CI/CD deploy). Since it's one build per institute either way, **colors are baked at build time** via `--dart-define` (`int.fromEnvironment`, a valid `const` expression — safe across all ~2,460 `AppColors.primaryBrand` call sites including `const` ones). **Logo + app display name are runtime-fetched** (cached fallback) since their ~10 call sites can afford it and it lets an institute update either without a new store submission. |
| 5 | Birthdays: staff included? Batch list for students? | **Students only** for now; **yes**, add "birthdays in my batch" for students |
| 6 | Homework: resubmission / deadline lock? | **Resubmission allowed until deadline**; locked after due date |
| 7 | ID card: student-accessible route? | **Add new student route**; keep existing institute route as-is |

---

## Final Task List

### App (Flutter/GetX) — `tuoora`

**Foundations**
- [ ] Rebrand `README.md` + `pubspec.yaml` description (Tuoora, not fee_easy)

**Payments cleanup** — done 2026-09-02
- [x] Remove IAP controller/flow + `in_app_purchase` package
- [x] Remove offline proof-upload flow (screens + controller + binding)
- [x] Repoint "manage on web" view to a simple info/contact screen
- [x] Leave Razorpay as sole renewal path; clean up subscription UI

**Exams** — done 2026-09-02 (nested under Batch Details, not top-level — see note below)
- [x] Institute: create/edit/delete exam; enter & save marks, incl. max marks + exam type fields
- [x] Student: list exams + result detail
- [x] Models, repositories, controllers, bindings, routes

  Note: decision 2b assumed Homework/Timetable were top-level dashboard tiles; Homework is
  actually nested under Batch Details. Re-confirmed with the user and built Exams the same way
  (Batch Details → Exams tile), not as a dashboard grid tile. Student-side Exams is a standalone
  screen reached from the Profile tab's quick-actions grid (mirrors Reports/Study Material),
  not a 6th bottom-nav tab.

  **UI-verified live on Android** (real device build against the local backend, not just
  `flutter analyze`) — full institute flow (create → list → enter/save marks → edit → delete)
  and full student flow (list → Upcoming/Results tabs → result detail) all confirmed working
  end-to-end. Two real bugs were caught and fixed only because of this live pass:
  1. `Obx` wrapping a non-reactive read (`controller.isEditing`, a plain field, not `.obs`)
     crashed the Add/Edit Exam screen and the per-row absent-toggle on Marks entry with GetX's
     "improper use of GetX" error — both were unreachable via static analysis. Fixed by removing
     the unnecessary `Obx` wrappers (the outer list-level `Obx` already covers reactivity).
  2. Exam dates displayed one day early and would have silently corrupted by another day on
     every edit: the `Exam` model's `'exam_date' => 'date'` Eloquent cast serializes through
     UTC on JSON encode, which shifts the calendar day back for any app timezone ahead of UTC
     (`Asia/Kolkata`, UTC+5:30, is this app's `APP_TIMEZONE`). Fixed at the root — changed the
     cast to `'date:Y-m-d'` in `app/Models/Exam.php` (also fixes it for the web/admin panel and
     any other API consumer) — plus a defensive `.toLocal()` on the Flutter side.
  iOS was not build/UI-tested this session (user opted out); the fixes above are Dart/PHP-level
  and apply equally to iOS since it's the same codebase, but iOS-native issues (if any) remain
  unverified.

**Timetable** — code complete 2026-09-02 (not on-device verified — see note below)
- [x] Institute: manage per-batch weekly timetable, staff shown as plain-text label
- [x] Student: view schedule
- [x] Models, repo, controllers, views

  Note: backend mobile API was missing `update`/`destroy` (web panel had them, app didn't) —
  added both, mirroring the web controller. Entry points: Batch Details → Timetable tile
  (matches Exams/Homework/Attendance) AND an institute Home-dashboard tile (routes to
  Batches, since a slot must belong to one). Student side: Profile tab quick-actions grid.
  Applied the Exams-verification lesson proactively: audited every new `Obx` for the
  lazy-`ListView.builder`-outside-tracking-window bug before ever launching the app, found
  and fixed it in the day-selector chips (both institute and student screens) pre-emptively.
  Unlike Exams, this module was NOT driven on a real device/emulator this session (stopped
  partway through per instruction) — only `flutter analyze` + backend tinker smoke tests.

**White-label** — code complete 2026-09-02 (not on-device verified — same standing instruction as Timetable; the only remaining gap is the web self-serve purchase button, tracked below, which is a pre-existing backend/web scope item rather than app-side work)
- [x] `AppColors.primaryBrand`/`primaryBrandLight` baked at build time via `int.fromEnvironment('BRAND_PRIMARY_COLOR', ...)` — full app-wide coverage, zero risk (avoided an earlier failed attempt at runtime-mutable colors that broke 113 `const` call sites; see note below)
  `AppTheme.lightTheme` now also derives `primary`/`primaryContainer` from `AppColors.primaryBrand` instead of a separate hardcoded palette
- [x] `BrandingService` (`GetxService`) — fetches `GET /app-branding?institute_id=` at launch (baked-in `int.fromEnvironment('INSTITUTE_ID', ...)`), `GetStorage`-cached with a 6s-timeout background refresh, wired into `main.dart` after `AuthService` init (`ApiClient`'s request modifier needs `AuthService` registered first)
- [x] `AppLogo` widget — drop-in for the old hardcoded `Image.asset(AppImages.logoWithName)`, shows the fetched logo with graceful fallback to the bundled asset; swapped into all 7 call sites (signup, OTP, splash, login, forgot/reset password, role selection)
- [x] `GetMaterialApp(title:)` now reads `BrandingService.appName` when registered
- [x] Institute-facing purchase + branding UI (`WhiteLabelScreen`, reachable from Profile → "White Label"): Razorpay checkout against the backend endpoints below, then a branding form (app name, logo picker, preset color swatches) once active, with an ops-review status banner (submitted / confirmed)
  - iOS gets the same "manage on web" external-browser redirect as subscriptions instead of a native Razorpay sheet, per Apple's IAP policy for real-money digital purchases — generalized the existing `SubscriptionManageOnWebView` widget to take a title/message/url instead of duplicating it. Lands on `https://tuoora.com/institute/plans` (the page that already displays the add-on) since no dedicated web purchase flow exists yet — **that web purchase flow is still a gap**, tracked below.
- [x] Android build tooling — used Gradle-property overrides (`-PappId=...`, `-PappLabel=...`,
      read by `android/app/build.gradle.kts` and referenced from `AndroidManifest.xml` via
      `${appLabel}`/`applicationId`) rather than declared product flavors, since flavors need
      every institute enumerated ahead of time in the Gradle file — doesn't fit an open-ended
      per-institute model. Every normal `flutter build`/`flutter run` with neither property set
      is byte-for-byte unaffected (falls back to `com.app.tuoora`/"Tuoora").
  - `tool/build_white_label.sh` — one script, one institute, one build: takes `--institute-id`,
    `--app-name`, `--package-id`, `--logo`, `--primary-color` (+ optional `--primary-color-light`,
    `--google-services-json`, `--format apk|appbundle`); regenerates the launcher icon via a
    throwaway `flutter_launcher_icons` config (never touches `pubspec.yaml`'s own default-icon
    config) and runs `flutter build` with the Gradle properties + `--dart-define`s above.
  - **Two things it deliberately does NOT automate, flagged loudly in its own `--help` header:**
    (1) **Firebase** — `google-services.json` is registered to `com.app.tuoora` only; a different
    `--package-id` needs its own Firebase Android app + google-services.json or push notifications
    silently break. (2) **Play Console + signing** — package IDs are permanent/unique per Play
    Store listing, so each institute needs its own listing and signing key; the script just signs
    with whatever `android/keystore.properties` currently points at.
  - Recommends running on a throwaway `whitelabel/<institute-slug>` branch (never merged) so the
    regenerated icon files have a place to live without dirtying `main`.
- [ ] Web: give the institute-facing `institute/plans` page (or a dedicated page) a real self-serve purchase button — today it only *displays* the add-on (pricing/description), matching the mobile app's now-built purchase flow requires the same on web for iOS users and for institutes who'd rather buy from the panel directly
- Not on-device tested this session (standing instruction to skip device testing for the remainder of this roadmap)

**Follow-up: generic Add-ons catalog** — done 2026-09-02, requested after White-Label shipped, so
future paid add-ons don't each need a hand-rolled settings block. Full plan at
`~/.claude/plans/scalable-leaping-sunset.md`. Institute-facing UX is deliberately simple per
explicit confirmation: institutes only ever see a browsable list and can purchase — enabling or
disabling an add-on catalog-wide is admin-only, there is no institute-facing toggle. White Label
is the only real entry for now; more can be added later without new code for the common cases.
- [x] New `AddOn` catalog model/table (admin-manageable at `/admin/addons`, own CRUD page —
  create/edit/delete/status-toggle, mirrors the existing `PlanController` pattern) — `kind` is
  `flag` (purchase = yes/no entitlement), `quota` (purchase sets a numeric limit), or `custom`
  (needs its own backend code, e.g. White Label's branding-review flow — the catalog only
  manages its pricing/listing, kind is immutable once created).
- [x] Consolidated four places that had each independently rebuilt the White Label pricing JSON
  with slightly different shapes/hardcoded feature lists (`InstituteWhiteLabelController`,
  `Api/V1/PlanController`, `InstituteSubscriptionController`, `Web/PlanController`) onto one
  `AddOn::toApiArray()`. Removed the second of two independent admin write paths for the same
  settings (`PlanController::updateAddon()` + its form on the Plans page, and the five
  `mobile_app_whitelabel_*` fields on the general Settings page) — pricing now has exactly one
  edit surface. White Label's purchase/verify/branding endpoints are untouched; only where their
  pricing config comes from changed. A migration seeds the catalog from whatever was already
  configured, so nothing reset.
- [x] Generic institute-facing API (`GET /institute/addons`, `POST /institute/addons/{id}/
  create-order`, `.../verify-payment`) for `flag`/`quota` kind add-ons — reuses the same
  Razorpay-invoice + HMAC-verify pattern as White Label's own endpoints (not extracted into a
  shared helper, only two call sites). `custom`-kind add-ons 422 here; the app deep-links those
  straight to their own dedicated screen instead.
- [x] `Institute::hasAddOn(slug)` / `addOnQuota(slug)` helpers — the "zero new backend code"
  payoff for a future `flag`/`quota` add-on: check `hasAddOn('some_slug')` at the one point that
  needs to gate on it, nothing else to build.
- [x] App: new `AddOnsScreen` (institute) lists all enabled add-ons; a `custom`-kind card (White
  Label) deep-links to the existing untouched `WhiteLabelScreen`, `flag`/`quota` cards get a
  small generic Razorpay "Buy Now" flow. Profile's "White Label" nav card is now "Add-ons" and
  points here; White Label's own app code was not touched.
- **Verified live** (temp server + real seeded institute, cleaned up after): regression-checked
  all three swapped read-sites return byte-identical JSON to pre-swap; admin CRUD incl. both
  delete guards (blocks deleting a `custom`-kind row, blocks deleting a row with purchases);
  full generic purchase flow end-to-end via a manually-computed valid HMAC signature (Razorpay
  itself isn't reachable from this local `.env` — same pre-existing limitation White Label's own
  `createOrder()` already had, not a regression); confirmed `hasAddOn()` flips true post-verify.
  Not on-device tested (same standing instruction).

  **Note — a mistake made and fully reverted along the way:** first attempt made `primaryBrand`/`primaryBrandLight` non-`const` for runtime mutation. A single-line grep for `const.*AppColors\.(primaryBrand|primaryBrandLight)` missed 113 compile errors caused by multi-line/nested `const` contexts `flutter analyze` caught. Fully reverted (colors back to `static const`, all 15+ call sites' `const` restored) and verified clean via `git diff` + `flutter analyze` before switching to the `int.fromEnvironment` approach actually used above.

**Birthdays** — done 2026-09-02 (endpoints verified live via curl against a local server + real seeded
records; not on-device UI tested — same standing instruction as Timetable/White-label)
- [x] Institute: "today's birthdays" list/card (students only) + send-wish action
  - Backend for this was mostly already there and unused by the app: `GET /institute/birthdays`
    (`InstituteStudentController::birthdays()`) already existed, returning students institute-wide
    whose birthday falls in the next 30 days. Lightly edited it to eager-load `batch` and return a
    curated field set (id/name/photo/dob/is_birthday_today/batch_id/batch_name) instead of raw
    `Student` models — the raw version was incidentally leaking `fcm_token`/`otp` to the institute
    JSON response (low-severity since it's the institute's own students, but free to fix while touching it).
  - "Send wish" needed zero new backend work — reuses the existing generic
    `POST /institute/notifications/send-push` (`target_type: specific_students`) that already
    handles FCM + DB notification delivery.
  - App: `BirthdayModel`, `BirthdayController` (fetch + per-row `sendWish` with a `sendingWishFor`
    id-set for per-row loading state), `BirthdaysScreen` (Today / Upcoming sections), a
    `TodaysBirthdayCard` dashboard banner (collapses to nothing when no birthdays today, mirrors
    `SubscriptionBanner`'s pattern) wired above the module grid, plus a "Birthdays" module tile
    (new `birthday-cake.svg` icon) as the always-available entry point.
- [x] Student: kept the existing self-birthday dialog (`BirthdayWishDialog`, push-triggered via
  `BirthdayNotificationHandler` — unrelated to this feature's polling, already fine, untouched);
  added "birthdays in my batch" as a new list.
  - New backend endpoint `GET /student/birthdays` (`StudentBirthdayController`) — same next-30-days
    logic as the institute one but batch-scoped and returning ONLY a safe curated field set
    (no contact info, fees, or address) since this is visible to a fellow student, not staff.
    Marks `is_me` on the viewer's own row.
  - App: `StudentBirthday` model, `StudentBirthdayRepository`/`StudentBirthdayController`
    (mirrors the just-built Timetable pattern exactly — no shared `InstituteRepositoryImpl`
    layer, just `ApiClient` direct), `BatchBirthdaysScreen`, tile added to the Profile
    quick-actions grid next to Timetable.
  - **Verified live**: temporarily set a seeded student's `dob` to today via `tinker`, confirmed
    both endpoints return correct curated JSON (`is_birthday_today: true`, `is_me: true` on the
    student side, `batch_name` resolved on the institute side), confirmed `send-push` accepts the
    wish payload end-to-end (0 sent/1 failed in the response is expected — the test student has no
    real FCM token in the local DB), then restored the `dob` and revoked the test tokens.

**Homework submission** — done 2026-09-02 (backend verified live via curl against a throwaway
homework record + real student; app side not on-device tested — same standing instruction)
- [x] Submit UI on homework detail: text note + file attachment
  - **Found a real, pre-existing bug while starting this**: `homework_center.dart` /
    `homework_detail_screen.dart` (routed at `AppRoutes.studentHomeworkDetail`) are dead code —
    fully mocked (hardcoded "Advanced Calculus", fake tutor, fake progress bar), never navigated
    to with an actual homework id, and not reachable from the live bottom-nav Homework tab at all.
    The tab that's actually live is `StudentAssignmentsScreen` → `StudentAssignmentDetailScreen`,
    wired to real data via `AssignmentsController`/`StudentHomeworkRepository` — that's the one
    this task actually needed, and where the new submit form was built. Left the dead files alone
    (out of scope to delete unless asked).
  - Backend: added `note` (text) + `attachment` (file, max 10MB) columns to `homework_submissions`
    (new migration + `HomeworkSubmission::$fillable` + an `attachment_url` accessor). Rewrote
    `StudentHomeworkController::submit()` to accept both (at least one required — a submission
    with neither is a no-op), and to `updateOrCreate` instead of always-create so resubmission
    works. Also surfaced `note`/`attachment_url` in `index()`/`show()`'s `submission` sub-object,
    and fixed a related pre-existing bug in the same response shape: the *homework's own*
    `attachment_url` (teacher-provided) was returning a raw unusable storage path instead of a
    real URL in both `index()` and `show()` — one-line fix to match the convention already used
    elsewhere (`asset('storage/' . ...)`).
  - App: `Assignment` model gained `submissionNote`/`submissionAttachmentUrl` plus a true
    `dueDatePassed` flag (the existing `isOverdue` field is display-only and gets forced to
    `false` once completed, which would have wrongly allowed "resubmission" after the deadline
    on an already-submitted assignment — `dueDatePassed` is deadline-only, independent of
    submission status). `AssignmentsController` gained a `noteController` + picked-file state
    (via the already-available `file_picker` package, mirroring the institute-side homework
    attachment pattern) and prefills both from the assignment's existing submission when opening
    it, so resubmitting starts from what was last sent rather than a blank form. Detail screen's
    submit button became a real form (note field + optional-file picker + submit/resubmit button),
    replacing the old bare tap-to-submit action; the "submission closed" locked state now keys off
    `dueDatePassed` instead of the completion-blind `isOverdue`.
- [x] Disable/lock submit action after deadline; allow resubmission before it
  - **Verified live**: created a throwaway homework (future due date) for a real seeded student,
    confirmed via curl: note-only submit succeeds, resubmit with a different note succeeds and
    overwrites, submitting with neither note nor attachment correctly 422s, resubmitting with a
    real file attachment succeeds and `show()` reflects the latest note+attachment, then pushed
    the due date into the past and confirmed a further resubmit attempt correctly 403s — deadline
    lock applies equally to first submissions and resubmissions, exactly per the locked decision.
    Cleaned up the throwaway homework/submission/file/token afterward.

**Student ID card** — done 2026-09-02 (backend verified live via curl against a real seeded
student; app side not on-device tested — same standing instruction)
- [x] View + download/share ID card (QR) on student profile
  - Found the institute already has an analogous unused backend endpoint
    (`InstituteStudentController::idCard()`, `/institute/students/{id}/id-card` — QR payload +
    verification hash) that the app never actually calls; the institute app screen instead
    renders a QR-less card straight from already-fetched profile fields. Per the locked decision,
    left that institute endpoint/screen untouched and added a new student-scoped equivalent:
    `StudentIdCardController@show` at `GET /student/id-card`, same payload shape scoped to
    `$request->user()` (no path id — a student can only ever fetch their own card) instead of an
    institute-supplied `{id}`.
  - App: new `qr_flutter` (QR render), `share_plus` (native share sheet — pinned to `^12.0.2`,
    not the latest 13.x, because that needs `win32 ^6` and this project's `device_info_plus`
    pins `win32 ^5`; not touching that unrelated pin to chase a newer share_plus), and `gal`
    (save-to-gallery, replaces the unmaintained `image_gallery_saver`) dependencies. Added the
    Android (`requestLegacyExternalStorage`, `WRITE_EXTERNAL_STORAGE` was already declared) and
    iOS (`NSPhotoLibraryAddUsageDescription`) permission entries `gal` needs.
  - `StudentIdCardScreen` renders the card inside a `RepaintBoundary`, then Share/Download both
    capture that boundary to PNG bytes (`toImage`/`toByteData`, pure Flutter SDK, no screenshot
    package needed) — Share hands the bytes to `share_plus` via `XFile.fromData` (in-memory,
    no temp file/`path_provider` needed), Download hands them to `Gal.putImageBytes`. New entry
    point: Profile → "ID Card" tile, next to Timetable/Birthdays.

---

### Backend (Laravel 10) — `tuoora-backend`

- [ ] Confirm every new endpoint's response shape matches new app models
- [x] Leave offline-renewal (approve/reject) + IAP-verify routes as-is (web/admin use)
- [x] Exams — added missing `exam_type` column/migration + validation (module was NOT fully
      complete as assumed — `total_marks` already covered "max marks" but exam type was missing).
      46 pending migrations were also caught up on the local DB along the way.
- [x] Timetable — was NOT fully complete as assumed: `index`/`store` existed, `update`/`destroy`
      were missing from the mobile API (web panel had them). Added both.
- [x] **White-label:** the existing pricing/display scaffolding (`SystemSetting`-backed addon config,
      already surfaced read-only in both the super-admin settings form and the institute plans page)
      was left alone; built the missing purchase/tracking/branding/review pieces around it:
  - [x] `InstituteWhiteLabel` model + migration — purchase status (pending/active/cancelled),
        Razorpay order/payment/signature, submitted app_name/logo/colors, `admin_confirmed_at` review gate
  - [x] `Api/V1/InstituteWhiteLabelController` — `show`/`createOrder` (Razorpay invoice, mirrors the
        subscription flow)/`verifyPayment` (HMAC)/`updateBranding` (multipart logo upload, gated on active status)
  - [x] `Api/V1/AppBrandingController` — public `GET /api/v1/app-branding?institute_id=` used by the app's
        `BrandingService`; returns `white_labeled: false` unless active AND branding is complete
  - [x] `Web/WhiteLabelController` + `whitelabel/index.blade.php` — super-admin review queue
        (paginated, status filter) + confirm action; nav link added under admin layout
  - [ ] Institute-facing web self-serve purchase button — still just a display, no checkout on web
        (see App section note above; only the mobile app's purchase flow was built this session)
- [ ] Birthdays — add batch-birthday feed endpoint for students (new, to support app list)
- [x] Homework submission — deadline-lock was already enforced server-side (not just UI); added
      `note`/`attachment` columns + resubmission support to `submit()`, see App section for detail.
- [ ] **ID card** — add new student-accessible `id-card` route (existing institute route stays)
