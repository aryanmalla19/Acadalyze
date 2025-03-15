import React, { useState } from "react";
import TeacherTableSearch from "../TeacherPage/TeacherTableSearch";
import TableList from "../TeacherPage/TableList";
import Pagination from "../TeacherPage/Pagination";

const ParentList = () => {
  const columns = [
    {
      header: "Parents Name",
      accessor: "name",
      className: "hidden md:table-cell",
    },
    {
      header: "Student Name",
      accessor: "sname",
      className: "hidden md:table-cell",
    },
    { header: "Phone", accessor: "phone", className: "hidden md:table-cell" },
    {
      header: "Address",
      accessor: "address",
      className: "hidden md:table-cell",
    },
    {
      header: "Actions",
      accessor: "actions",
      className: "hidden md:table-cell",
    },
  ];

  const allTeachers = [
    {
      name: "Neetu Rai",
      sname: "neetu rai",
      phone: "1234567890",
      address: "Dhapasi",
    },
    {
      name: "Sujata Chaudhary",
      sname: "sujata chaudhary, neetu chaudhary",
      phone: "9876543210",
      address: "Kathmandu",
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
        <h1 className="hidden md:block text-lg font-semibold">All Parents</h1>
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

export default ParentList;
