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
        $events = $this->calendarModel->getAllEvents();
        $userID = auth()->id();
   
        if (!$userID) {
            return redirect()->to('/login')->with('error', 'You must login to access this page.');
        }
   
        $formattedEvents = [];
        foreach($events as $event) {
            $formattedEvents[] = [
                'title' => $event['title'],
                'start' => $event['start_date'],
                'end' => $event['end_date'] ?: $event['start_date'],
                'all_day' => (bool)$event['all_day'],
                'id' => $event['id']
            ];
        }
   
        return view('PMS/calendar.php', [
            'events' => json_encode($formattedEvents),
            'eventIds' => array_column($formattedEvents, 'id'),
        ]);
    }

    public function create()
    {
        $data = [
            'title' => $this->request->getPost('title'),
            'start_date' => $this->request->getPost('start_date'),
            'end_date' => $this->request->getPost('end_date'),
            'all_day' => $this->request->getPost('all_day') ? 1 : 0,
        ];

        $this->calendarModel->insertEvent($data);

        return $this->response->setJSON(['success' => true]);
    }

    public function updateEvent()
    {
        $eventId = $this->request->getPost('eventId');
        $data = [
            'title' => $this->request->getPost('title'),
            'start_date' => $this->request->getPost('start_date'),
            'end_date' => $this->request->getPost('end_date'),
            'all_day' => $this->request->getPost('all_day') ? 1 : 0,
        ];

        $this->calendarModel->updateEvent($eventId, $data);

        return $this->response->setJSON(['success' => true]);
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