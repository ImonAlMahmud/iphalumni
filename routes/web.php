<?php
declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DirectoryController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\StoriesController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\Portal\DashboardController as PortalDashboard;
use App\Http\Controllers\Portal\ProfileController;
use App\Http\Controllers\Portal\MembershipController as PortalMembership;
use App\Http\Controllers\Portal\JobController as PortalJobController;
use App\Http\Controllers\Portal\StoryController;
use App\Http\Controllers\Portal\FinancialController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\AlumniController as AdminAlumni;
use App\Http\Controllers\Admin\StudentsController as AdminStudents;
use App\Http\Controllers\Admin\MembershipController as AdminMembership;
use App\Http\Controllers\Admin\NewsController as AdminNews;
use App\Http\Controllers\Admin\EventController as AdminEvent;
use App\Http\Controllers\Admin\GalleryController as AdminGallery;
use App\Http\Controllers\Admin\CommitteeController as AdminCommittee;
use App\Http\Controllers\Admin\StoriesController as AdminStories;
use App\Http\Controllers\Admin\ReportController as AdminReport;
use App\Http\Controllers\Admin\SettingsController as AdminSettings;
use App\Http\Controllers\Admin\EmailTemplatesController;
use App\Http\Controllers\Payment\UddoktaPayController;

// ── Public Routes ─────────────────────────────────────────────────────────────

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/constitution', [HomeController::class, 'constitution'])->name('constitution');
Route::get('/constitution/pdf', [HomeController::class, 'constitutionPdf'])->name('constitution.pdf');
Route::get('/history', [HomeController::class, 'history'])->name('history');
Route::get('/committee', [HomeController::class, 'committee'])->name('committee');
Route::get('/faq', [HomeController::class, 'faq'])->name('faq');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::post('/contact', [HomeController::class, 'handleContact'])->name('contact.send');
Route::get('/sitemap.xml', [HomeController::class, 'sitemap'])->name('sitemap');
Route::get('/mentorship', [HomeController::class, 'mentorship'])->name('mentorship');

// Directory
Route::get('/directory', [DirectoryController::class, 'index'])->name('directory');
Route::get('/directory/{id}', [DirectoryController::class, 'show'])->name('directory.show');
Route::post('/directory/{id}/request-contact', [DirectoryController::class, 'sendContactRequest'])->name('directory.contact_request');

// Success Stories (public)
Route::get('/stories', [StoriesController::class, 'index'])->name('stories');
Route::get('/stories/{slug}', [StoriesController::class, 'show'])->name('stories.show');

// News
Route::get('/news', [NewsController::class, 'index'])->name('news');
Route::get('/news/{id}/pdf', [NewsController::class, 'printPdf'])->name('news.pdf');
Route::get('/news/{slug}', [NewsController::class, 'show'])->name('news.show');

// Events
Route::get('/events', [EventController::class, 'index'])->name('events');
Route::get('/events/{slug}', [EventController::class, 'show'])->name('events.show');
Route::post('/events/{slug}/register', [EventController::class, 'register'])
    ->middleware('auth.alumni')
    ->name('events.register');

// Gallery
Route::get('/gallery', [GalleryController::class, 'index'])->name('gallery');
Route::get('/gallery/{id}', [GalleryController::class, 'album'])->name('gallery.album');
Route::post('/gallery/{id}/upload', [GalleryController::class, 'upload'])->middleware('auth.alumni')->name('gallery.upload');

// Job Circulars (Public)
Route::get('/jobs', [JobController::class, 'index'])->name('jobs');
Route::get('/jobs/{id}', [JobController::class, 'show'])->name('jobs.show');
Route::post('/jobs/apply', [JobController::class, 'apply'])->name('jobs.apply');
Route::post('/jobs/subscribe', [JobController::class, 'subscribe'])->name('jobs.subscribe');
Route::get('/jobs/unsubscribe/{token}', [JobController::class, 'unsubscribe'])->name('jobs.unsubscribe');

// Donation
Route::get('/donate', [DonationController::class, 'index'])->name('donate');
Route::post('/donate', [DonationController::class, 'store'])->name('donate.store');

// QR Verify (public)
Route::get('/verify/{code}', [PortalMembership::class, 'verify'])->name('membership.verify');

// ── Auth Routes ───────────────────────────────────────────────────────────────

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/register', [AuthController::class, 'registerForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
    Route::get('/forgot-password', [AuthController::class, 'forgotForm'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'forgot'])->name('password.email');
    Route::get('/reset-password', [AuthController::class, 'resetForm'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'reset'])->name('password.update');
});

Route::post('/send-verification-code', [AuthController::class, 'sendVerificationCode'])->name('auth.verify_code');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
Route::post('/logout', [AuthController::class, 'logout']);

// ── Alumni Portal Routes ──────────────────────────────────────────────────────

Route::prefix('/portal')->middleware('auth.alumni')->name('portal.')->group(function () {
    Route::get('', [PortalDashboard::class, 'index'])->name('dashboard');
    Route::get('/id-card', [PortalDashboard::class, 'idCard'])->name('id_card');
    Route::get('/notifications', [PortalDashboard::class, 'notifications'])->name('notifications');

    // Profile
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/avatar', [ProfileController::class, 'uploadAvatar'])->name('profile.avatar');
    Route::post('/profile/signature', [ProfileController::class, 'uploadSignature'])->name('profile.signature');
    Route::get('/profile/education', [ProfileController::class, 'education'])->name('profile.education');
    Route::post('/profile/education', [ProfileController::class, 'saveEducation'])->name('profile.education.save');
    Route::post('/profile/education/delete', [ProfileController::class, 'deleteEducation'])->name('profile.education.delete');
    Route::get('/profile/employment', [ProfileController::class, 'employment'])->name('profile.employment');
    Route::post('/profile/employment', [ProfileController::class, 'saveEmployment'])->name('profile.employment.save');
    Route::post('/profile/employment/delete', [ProfileController::class, 'deleteEmployment'])->name('profile.employment.delete');

    // Settings
    Route::get('/settings', [ProfileController::class, 'settings'])->name('settings');
    Route::post('/settings', [ProfileController::class, 'updateSettings'])->name('settings.update');

    // Contact requests
    Route::get('/contact-requests', [ProfileController::class, 'contactRequests'])->name('contact_requests');
    Route::post('/contact-requests/{id}/accept', [ProfileController::class, 'acceptContactRequest'])->name('contact_requests.accept');
    Route::post('/contact-requests/{id}/reject', [ProfileController::class, 'rejectContactRequest'])->name('contact_requests.reject');
    Route::post('/contact-requests/{id}/delete', [ProfileController::class, 'deleteContactRequest'])->name('contact_requests.delete');

    // Membership
    Route::get('/membership', [PortalMembership::class, 'index'])->name('membership');
    Route::post('/membership/apply', [PortalMembership::class, 'apply'])->name('membership.apply');
    Route::get('/membership/qr', [PortalMembership::class, 'qrId'])->name('membership.qr');
    Route::post('/membership/payment/uddoktapay', [UddoktaPayController::class, 'initiate'])->name('membership.payment.uddoktapay');
    Route::get('/membership/payment/uddoktapay/success', [UddoktaPayController::class, 'success'])->name('membership.payment.uddoktapay.success');
    Route::get('/membership/payment/uddoktapay/cancel', [UddoktaPayController::class, 'cancel'])->name('membership.payment.uddoktapay.cancel');

    // Portal Stories
    Route::get('/stories', [StoryController::class, 'index'])->name('stories');
    Route::get('/stories/create', [StoryController::class, 'create'])->name('stories.create');
    Route::post('/stories', [StoryController::class, 'store'])->name('stories.store');
    Route::get('/stories/{id}/edit', [StoryController::class, 'edit'])->name('stories.edit');
    Route::post('/stories/{id}/update', [StoryController::class, 'update'])->name('stories.update');
    Route::post('/stories/{id}/delete', [StoryController::class, 'delete'])->name('stories.delete');

    // Portal Jobs
    Route::get('/jobs', [PortalJobController::class, 'index'])->name('jobs');
    Route::get('/jobs/create', [PortalJobController::class, 'create'])->name('jobs.create');
    Route::post('/jobs', [PortalJobController::class, 'store'])->name('jobs.store');
    Route::get('/jobs/{id}/edit', [PortalJobController::class, 'edit'])->name('jobs.edit');
    Route::post('/jobs/{id}/update', [PortalJobController::class, 'update'])->name('jobs.update');
    Route::get('/jobs/{id}/applications', [PortalJobController::class, 'applications'])->name('jobs.applications');
    Route::post('/jobs/toggle-status', [PortalJobController::class, 'toggleStatus'])->name('jobs.toggle_status');

    // Financials
    Route::get('/financials', [FinancialController::class, 'index'])->name('financials');
    Route::get('/financials/funds', [FinancialController::class, 'funds'])->name('financials.funds');
    Route::post('/financials/fund', [FinancialController::class, 'storeFund'])->name('financials.fund.store');
    Route::get('/financials/funds/export/excel', [FinancialController::class, 'exportFundsExcel'])->name('financials.funds.excel');
    Route::get('/financials/funds/export/pdf', [FinancialController::class, 'exportFundsPdf'])->name('financials.funds.pdf');
    Route::get('/financials/expenses', [FinancialController::class, 'expenses'])->name('financials.expenses');
    Route::post('/financials/expense', [FinancialController::class, 'storeExpense'])->name('financials.expense.store');
    Route::get('/financials/expenses/export/excel', [FinancialController::class, 'exportExpensesExcel'])->name('financials.expenses.excel');
    Route::get('/financials/expenses/export/pdf', [FinancialController::class, 'exportExpensesPdf'])->name('financials.expenses.pdf');
    Route::get('/financials/budgets', [FinancialController::class, 'budgets'])->name('financials.budgets');
    Route::post('/financials/budget', [FinancialController::class, 'storeBudget'])->name('financials.budget.store');
    Route::get('/financials/budgets/export/excel', [FinancialController::class, 'exportBudgetsExcel'])->name('financials.budgets.excel');
    Route::get('/financials/budgets/export/pdf', [FinancialController::class, 'exportBudgetsPdf'])->name('financials.budgets.pdf');
});

// ── Admin Routes ──────────────────────────────────────────────────────────────

Route::prefix('/admin')->middleware('auth.admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('', [AdminDashboard::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [AdminDashboard::class, 'index']);

    // Broadcast
    Route::get('/broadcast', [HomeController::class, 'adminBroadcast'])->name('broadcast');
    Route::post('/broadcast/send', [HomeController::class, 'adminBroadcastSend'])->name('broadcast.send');

    // Students Reference Database Directory
    Route::get('/students', [AdminStudents::class, 'index'])->name('students');
    Route::get('/students/export/csv', [AdminStudents::class, 'exportCsv'])->name('students.export_csv');
    Route::get('/students/export/print', [AdminStudents::class, 'exportPrint'])->name('students.export_print');
    Route::post('/students/{id}/update', [AdminStudents::class, 'update'])->name('students.update');
    Route::post('/students/{id}/delete', [AdminStudents::class, 'delete'])->name('students.delete');

    // Alumni Management
    Route::get('/alumni', [AdminAlumni::class, 'index'])->name('alumni');
    Route::get('/alumni/export/excel', [AdminAlumni::class, 'exportExcel'])->name('alumni.export_excel');
    Route::get('/alumni/export/pdf', [AdminAlumni::class, 'exportPdf'])->name('alumni.export_pdf');
    Route::get('/alumni/export/cards-svg', [AdminAlumni::class, 'exportCardsSvg'])->name('alumni.export_cards_svg');
    Route::get('/alumni/{id}/card-svg/{side}', [AdminAlumni::class, 'downloadSingleCardSvg'])->name('alumni.card_svg');
    Route::get('/alumni/mapping', [AdminAlumni::class, 'mapping'])->name('alumni.mapping');
    Route::post('/alumni/map-student', [AdminAlumni::class, 'mapStudent'])->name('alumni.map_student');
    Route::get('/alumni/contact-requests', [AdminAlumni::class, 'contactRequests'])->name('alumni.contact_requests');
    Route::get('/alumni/{id}', [AdminAlumni::class, 'show'])->name('alumni.show');
    Route::post('/alumni/{id}/approve', [AdminAlumni::class, 'approve'])->name('alumni.approve');
    Route::post('/alumni/{id}/reject', [AdminAlumni::class, 'reject'])->name('alumni.reject');
    Route::post('/alumni/{id}/status', [AdminAlumni::class, 'updateStatus'])->name('alumni.status');
    Route::post('/alumni/{id}/toggle-featured', [AdminAlumni::class, 'toggleFeatured'])->name('alumni.toggle_featured');

    // Membership
    Route::get('/membership', [AdminMembership::class, 'index'])->name('membership');
    Route::post('/membership/grant-honorary', [AdminMembership::class, 'grantHonorary'])->name('membership.grant_honorary');
    Route::post('/membership/tier/{id}/update', [AdminMembership::class, 'updateTier'])->name('membership.tier.update');
    Route::post('/membership/{id}/approve', [AdminMembership::class, 'approve'])->name('membership.approve');
    Route::post('/membership/{id}/reject', [AdminMembership::class, 'reject'])->name('membership.reject');
    Route::post('/membership/{id}/delete', [AdminMembership::class, 'delete'])->name('membership.delete');

    // Stories
    Route::get('/stories', [AdminStories::class, 'index'])->name('stories');
    Route::get('/stories/create', [AdminStories::class, 'create'])->name('stories.create');
    Route::post('/stories', [AdminStories::class, 'store'])->name('stories.store');
    Route::get('/stories/{id}/preview', [AdminStories::class, 'preview'])->name('stories.preview');
    Route::get('/stories/{id}/edit', [AdminStories::class, 'edit'])->name('stories.edit');
    Route::post('/stories/{id}', [AdminStories::class, 'update'])->name('stories.update');
    Route::post('/stories/{id}/approve', [AdminStories::class, 'approve'])->name('stories.approve');
    Route::post('/stories/{id}/reject', [AdminStories::class, 'reject'])->name('stories.reject');
    Route::post('/stories/{id}/delete', [AdminStories::class, 'delete'])->name('stories.delete');
    Route::post('/stories/{id}/toggle-featured', [AdminStories::class, 'toggleFeatured'])->name('stories.toggle_featured');

    // News
    Route::get('/news', [AdminNews::class, 'index'])->name('news');
    Route::get('/news/create', [AdminNews::class, 'create'])->name('news.create');
    Route::post('/news', [AdminNews::class, 'store'])->name('news.store');
    Route::get('/news/{id}/edit', [AdminNews::class, 'edit'])->name('news.edit');
    Route::post('/news/{id}', [AdminNews::class, 'update'])->name('news.update');
    Route::post('/news/{id}/delete', [AdminNews::class, 'delete'])->name('news.delete');

    // Events
    Route::get('/events', [AdminEvent::class, 'index'])->name('events');
    Route::get('/events/create', [AdminEvent::class, 'create'])->name('events.create');
    Route::post('/events', [AdminEvent::class, 'store'])->name('events.store');
    Route::get('/events/{id}/edit', [AdminEvent::class, 'edit'])->name('events.edit');
    Route::post('/events/{id}', [AdminEvent::class, 'update'])->name('events.update');
    Route::post('/events/{id}/delete', [AdminEvent::class, 'delete'])->name('events.delete');
    Route::get('/events/{id}/financials', [AdminEvent::class, 'financials'])->name('events.financials');
    Route::post('/events/{id}/expenses', [AdminEvent::class, 'storeExpense'])->name('events.expenses.store');

    // Gallery
    Route::get('/gallery', [AdminGallery::class, 'index'])->name('gallery');
    Route::get('/gallery/create', [AdminGallery::class, 'createAlbum'])->name('gallery.create');
    Route::post('/gallery', [AdminGallery::class, 'storeAlbum'])->name('gallery.store');
    Route::get('/gallery/{id}', [AdminGallery::class, 'viewAlbum'])->name('gallery.view');
    Route::get('/gallery/{id}/edit', [AdminGallery::class, 'editAlbum'])->name('gallery.edit');
    Route::post('/gallery/{id}/edit', [AdminGallery::class, 'updateAlbum'])->name('gallery.update');
    Route::post('/gallery/{id}/delete', [AdminGallery::class, 'deleteAlbum'])->name('gallery.delete');
    Route::post('/gallery/{id}/photos', [AdminGallery::class, 'uploadPhotos'])->name('gallery.photos.upload');
    Route::post('/gallery/photos/{photo_id}/delete', [AdminGallery::class, 'deletePhoto'])->name('gallery.photo.delete');

    // Committee
    Route::get('/committee', [AdminCommittee::class, 'index'])->name('committee');
    Route::get('/committee/create', [AdminCommittee::class, 'create'])->name('committee.create');
    Route::post('/committee', [AdminCommittee::class, 'store'])->name('committee.store');
    Route::get('/committee/{id}/edit', [AdminCommittee::class, 'edit'])->name('committee.edit');
    Route::post('/committee/{id}', [AdminCommittee::class, 'update'])->name('committee.update');
    Route::post('/committee/{id}/toggle-finance', [AdminCommittee::class, 'toggleFinance'])->name('committee.toggle_finance');

    // Reports
    Route::get('/reports', [AdminReport::class, 'index'])->name('reports');
    Route::get('/reports/alumni', [AdminReport::class, 'alumni'])->name('reports.alumni');
    Route::get('/reports/membership', [AdminReport::class, 'membership'])->name('reports.membership');
    Route::get('/reports/donations', [AdminReport::class, 'donations'])->name('reports.donations');

    // Settings
    Route::get('/settings', [AdminSettings::class, 'index'])->name('settings');
    Route::post('/settings', [AdminSettings::class, 'update'])->name('settings.update');
    Route::post('/settings/logo', [AdminSettings::class, 'uploadLogo'])->name('settings.logo');
    Route::post('/settings/uddoktapay/test', [AdminSettings::class, 'testUddoktaPay'])->name('settings.uddoktapay.test');
    Route::post('/settings/smtp/test', [AdminSettings::class, 'testSmtp'])->name('settings.smtp.test');

    // Email templates
    Route::get('/email-templates', [EmailTemplatesController::class, 'index'])->name('email_templates');
    Route::post('/email-templates/send-test', [EmailTemplatesController::class, 'sendTest'])->name('email_templates.send_test');
});

// ── Automated Webhook Deployment Route ───────────────────────────────────────
Route::match(['GET', 'POST'], '/webhook/deploy', [\App\Http\Controllers\Webhook\DeployController::class, 'handle'])->name('webhook.deploy');
Route::post('/webhook/uddoktapay', [UddoktaPayController::class, 'webhook'])->name('webhook.uddoktapay');

// ── Public Storage Serving Route (Fallback for cPanel / Shared Hosting) ─────
Route::get('/storage/{path}', function (string $path) {
    $candidates = [
        public_path('storage/' . $path),
        storage_path('app/public/' . $path),
        storage_path($path),
        public_path('uploads/' . $path),
        storage_path('app/' . $path),
    ];
    foreach ($candidates as $filePath) {
        if (file_exists($filePath) && is_file($filePath)) {
            return response()->file($filePath);
        }
    }
    abort(404);
})->where('path', '.*')->name('storage.serve');


