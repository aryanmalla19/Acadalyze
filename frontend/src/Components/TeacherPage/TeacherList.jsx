import React, { useState } from "react";
import TeacherTableSearch from "./TeacherTableSearch";
import Pagination from "./Pagination";
import TableList from "./TableList";

const TeacherList = () => {
  const columns = [
    {
      header: "Teacher ID",
      accessor: "teacherid",
      className: "hidden md:table-cell",
    },
    { header: "Name", accessor: "name", className: "hidden md:table-cell" },
    {
      header: "Subject",
      accessor: "subject",
      className: "hidden md:table-cell",
    },
    { header: "Phone", accessor: "phone", className: "hidden md:table-cell" },
    {
      header: "Actions",
      accessor: "actions",
      className: "hidden md:table-cell",
    },
  ];

  const allTeachers = [
    {
      teacherid: "1234567890",
      name: "John Doe",
      subject: "Math, Geometry",
      phone: "1234567890",
      address: "123 Main St, USA",
    },
    {
      teacherid: "safds",
      name: "Jane Smith",
      subject: "Science, Physics",
      phone: "9876543210",
      address: "456 Elm St, USA",
    },
  ];

  const [query, setQuery] = useState(""); // Search Query State

  // Filter data based on search query
  const filteredData = allTeachers.filter((teacher) =>
    teacher.name.toLowerCase().includes(query.toLowerCase())
  );

  return (
    <div className="bg-white p-4 rounded-md flex-1 m-4">
      {/* HEADER */}
      <div className="flex items-center justify-between mt-4">
        <h1 className="hidden md:block text-lg font-semibold">All Teachers</h1>
        <div className="flex flex-col md:flex-row items-center gap-4 w-full md:w-auto md:text-left">
          {/* Pass query and setQuery to Search Component */}
          <TeacherTableSearch query={query} setQuery={setQuery} />
        </div>
      </div>

      {/* TEACHER LIST */}
      <div>
        <TableList columns={columns} data={filteredData} />
      </div>

      {/* PAGINATION */}
      <div>
        <Pagination />
      </div>
    </div>
  );
};

export default TeacherList;
