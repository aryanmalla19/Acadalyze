import React, { useState } from "react";
import Pagination from "../TeacherPage/Pagination";
import TableList from "../TeacherPage/TableList";
import TeacherTableSearch from "../TeacherPage/TeacherTableSearch";

const ClassesList = () => {
  const columns = [
    {
      header: "Grade",
      accessor: "name",
      className: "hidden md:table-cell",
    },
    {
      header: "Total Students",
      accessor: "total_no",
      className: "hidden md:table-cell",
    },

    {
      header: "Class Teacher",
      accessor: "teacher",
      className: "hidden md:table-cell",
    },

    {
      header: "Actions",
      accessor: "actions",
      className: "hidden md:table-cell",
    },
  ];

  const allData = [
    {
      name: "1",
      total_no: "25",
      teacher: "Neetu Rai",
    },
    {
      name: "2",
      total_no: "25",
      teacher: "Neetu Rai",
    },
    {
      name: "3",
      total_no: "25",
      teacher: "Neetu Rai",
    },
    {
      name: "4",
      total_no: "25",
      teacher: "Neetu Rai",
    },
  ];

  const [query, setQuery] = useState(""); // Search Query State

  // Filter data based on search query
  const filteredData = allData.filter((data) =>
    data.name.toLowerCase().includes(query.toLowerCase())
  );

  return (
    <div className="bg-white p-4 rounded-md flex-1 m-4">
      {/* HEADER */}
      <div className="flex items-center justify-between mt-4">
        <h1 className="hidden md:block text-lg font-semibold">All Classes</h1>
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

export default ClassesList;
