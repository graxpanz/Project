<?php
class WorkCalendarController extends Controller {
    private $bookingModel;

    public function __construct() {
        parent::__construct();
        $this->bookingModel = $this->model('Booking');
    }

    public function index() {
        $bookings = $this->bookingModel->getAllBookings();
        $data = [
            'title' => 'ปฏิทินการปฏิบัติงาน | Mira ศูนย์ความงามครบวงจร',
            'bookings' => $bookings,
        ];
        $this->view('work_calendar/index', $data);
    }
}