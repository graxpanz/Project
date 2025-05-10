<?php
class DashboardController extends Controller {
    private $bookingModel;
    private $customerModel;
    private $serviceModel;
    private $financeModel;
    private $supplyModel;
    private $userModel;
    private $feedbackModel;

    public function __construct() {
        parent::__construct();
        $this->bookingModel = $this->model('Booking');
        $this->customerModel = $this->model('Customer');
        $this->serviceModel = $this->model('Service');
        $this->financeModel = $this->model('Finance');
        $this->supplyModel = $this->model('Supply');
        $this->userModel = $this->model('User');
        $this->feedbackModel = $this->model('Feedback');
    }

    public function index() {
        $current_date = date('Y-m-d');
        $start_date = isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-01');
        $end_date = isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-t');
        $current_year = date('Y');
        $current_month = date('m');

        $total_customers = $this->customerModel->countActiveCustomers();
        $total_services = $this->serviceModel->countActiveServices();
        $total_bookings = $this->bookingModel->countBookingsByDateRange($start_date, $end_date);
        $revenue_summary = $this->financeModel->getSummaryByDateRange($start_date, $end_date);
        $todays_bookings = $this->bookingModel->getBookingsByDate($current_date);
        $recent_bookings = $this->bookingModel->getRecentBookings(5);
        $booking_status_stats = $this->bookingModel->getBookingStatusStatsByDateRange($start_date, $end_date);
        $monthly_finances = $this->financeModel->getMonthlySummary($current_year);
        $popular_services = $this->bookingModel->getMostPopularServices($start_date, $end_date, 5);
        $revenue_by_service_type = $this->bookingModel->getRevenueByServiceType($start_date, $end_date);
        $new_customers_monthly = $this->customerModel->getNewCustomersMonthly($current_year);
        $top_employees = $this->bookingModel->getTopEmployees($start_date, $end_date, 5);
        $low_stock_supplies = $this->supplyModel->getLowStockSupplies();
        $feedback_stats = $this->feedbackModel->getRatingStats();
        $recent_feedback = $this->feedbackModel->getRecentFeedback(5);

        $data = [
            'title' => 'Dashboard | Mira ศูนย์ความงามครบวงจร',
            'total_customers' => $total_customers,
            'total_services' => $total_services,
            'total_bookings' => $total_bookings,
            'revenue_summary' => $revenue_summary,
            'todays_bookings' => $todays_bookings,
            'recent_bookings' => $recent_bookings,
            'booking_status_stats' => $booking_status_stats,
            'monthly_finances' => $monthly_finances,
            'popular_services' => $popular_services,
            'revenue_by_service_type' => $revenue_by_service_type,
            'new_customers_monthly' => $new_customers_monthly,
            'top_employees' => $top_employees,
            'low_stock_supplies' => $low_stock_supplies,
            'feedback_stats' => $feedback_stats,
            'recent_feedback' => $recent_feedback,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'current_year' => $current_year,
            'current_month' => $current_month
        ];
        
        $this->view('dashboard/index', $data);
    }
}