<?php

namespace App\Controllers;

use App\Models\CalendarModel;
use CodeIgniter\Controller;
use Faker\Provider\Base;

class CalendarController extends Controller {
    protected $calendarModel;

    public function __construct() {
        $this->calendarModel = new CalendarModel();
    }

    public function index() {
        // fetch events from the database.
        $events = $this->calendarModel->getAllEvents();
        $userID = auth()->id();

        if (!$userID) {
            return redirect()->to('/login')->with('error', 'You must login to access this page.');
        }

        // Format event data for calendar.
        $formattedEvents = [];
        foreach($events as $event) {
            $formattedEvents[] = [
                'title' => $event['title'],
                'start' => $event['start_date'],
                'end' => $event['end_date'] ?: $event['start_date'],
                'id' => $event['id']
            ];
        }

        // Pass formatted event data directly to the view.
        return view('PMS/calendar.php', [
            'events' => json_encode($formattedEvents),
            'eventIds' => array_column($formattedEvents, 'id'), // Pass an array of event IDs
        ]);
    }

    public function updateEvent() {
        // Get POST form submission data from form
        $id = $this->request->getPost('eventId');
        $title = $this->request->getPost('title');  // Corrected from 'editTitle'
        $start = $this->request->getPost('start');  // Corrected from 'editStart'
        $end = $this->request->getPost('end');      // Corrected from 'editEnd'

        // Fetch the event by its ID
        $event = $this->calendarModel->getEventById($id);

        if (!$event) {
            // Handle case where event with given ID does not exist
            // For example, redirect back to calendar with an error message
            return redirect()->to('/calendar')->with('error', 'Event not found.');
        }

        // Prepare data for updating the event
        $data = [
            'title' => $title,
            'start_date' => $start,
            'end_date' => $end,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        // Update the event
        $this->calendarModel->updateEvent($id, $data);

        // Redirect back to calendar page
        return redirect()->to('/calendar');
    }

    public function storeEvent() {
        // get the event from input.
        $title = $this->request->getPost('title');
        $start_date = $this->request->getPost('start');
        $end_date = $this->request->getPost('end');
        $all_day = $this->request->getPost('all_day');

        $data = [
            'title' => $title,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'all_day' => $all_day
        ];

        $eventID = $this->calendarModel->insertEvent($data);

        return redirect()->to('/calendar');
    }

    public function deleteEvent() {
        // Get the event ID from the request
        $eventId = $this->request->getPost('eventId');

        // Check if the event exists
        $event = $this->calendarModel->getEventById($eventId);
        if (!$event) {
            // Handle case where event with given ID does not exist
            // For example, redirect back to calendar with an error message
            return redirect()->to('/calendar')->with('error', 'Event not found.');
        }

        // Delete the event from the database
        $this->calendarModel->delete($eventId);

        // Redirect back to calendar page
        return redirect()->to('/calendar');
    }

}