import React, { useState } from "react";
import Calendar from "react-calendar";
import "react-calendar/dist/Calendar.css";
import { format } from "date-fns";

const EventCalender = () => {
  const [date, setDate] = useState(new Date());

  // Sample events data
  const events = [
    {
      date: "2024-08-09",
      title: "Parent-Teacher Meeting",
      time: "10:00 AM - 12:00 PM",
    },
    { date: "2025-03-09", title: "Math Exam", time: "02:00 PM - 04:00 PM" },
    { date: "2025-03-10", title: "Sports Day", time: "09:00 AM - 01:00 PM" },
  ];

  // Filter events based on selected date
  const formattedDate = format(date, "yyyy-MM-dd");
  const filteredEvents = events.filter((event) => event.date === formattedDate);

  return (
    <div className="p-6 bg-white shadow-lg rounded-xl max-w-lg mx-auto">
      <h2 className="text-2xl font-semibold text-gray-800 mb-4">
        Event Calendar
      </h2>

      {/* Calendar */}
      <div className="border border-gray-200 rounded-lg p-2">
        <Calendar onChange={setDate} value={date} className="w-full" />
      </div>

      {/* Events Section */}
      <div className="mt-6">
        <h3 className="text-lg font-medium text-gray-700">
          Events on {format(date, "PPP")}
        </h3>
        {filteredEvents.length > 0 ? (
          filteredEvents.map((event, index) => (
            <div
              key={index}
              className="mt-3 p-3 bg-blue-100 border-l-4 border-blue-500 rounded-lg"
            >
              <h4 className="font-semibold text-blue-800">{event.title}</h4>
              <p className="text-sm text-gray-600">{event.time}</p>
            </div>
          ))
        ) : (
          <p className="mt-2 text-gray-500">No events scheduled.</p>
        )}
      </div>
    </div>
  );
};

export default EventCalender;
