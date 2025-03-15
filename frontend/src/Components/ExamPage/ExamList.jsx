import React, { useState } from "react";
import Pagination from "../TeacherPage/Pagination";
import TableList from "../TeacherPage/TableList";
import TeacherTableSearch from "../TeacherPage/TeacherTableSearch";

const ExamList = () => {
  const columns = [
    { header: "Exam ID", accessor: "id", className: "hidden md:table-cell" },
    {
      header: "School ID",
      accessor: "school_id",
      className: "hidden md:table-cell",
    },
    {
      header: "Exam Name",
      accessor: "exam_name",
      className: "hidden md:table-cell",
    },
    {
      header: "Exam Date",
      accessor: "exam_date",
      className: "hidden md:table-cell",
    },
  ];

  const allData = [
    {
      id: 1,
      school_id: "123",
      exam_name: "Midterm Exam",
      exam_date: "2025-03-20",
    },
    {
      id: 2,
      school_id: "124",
      exam_name: "Final Exam",
      exam_date: "2025-06-15",
    },
  ];

  const [query, setQuery] = useState(""); // Search Query State

  // Filter data based on search query
  const filteredData = allData.filter((data) =>
    data.exam_name.toLowerCase().includes(query.toLowerCase())
  );

  return (
    <div className="bg-white p-4 rounded-md flex-1 m-4">
      {/* HEADER */}
      <div className="flex items-center justify-between mt-4">
        <h1 className="hidden md:block text-lg font-semibold">All Exams</h1>
        <div className="flex flex-col md:flex-row items-center gap-4 w-full md:w-auto md:text-left">
          {/* Pass query and setQuery to Search Component */}
          <TeacherTableSearch query={query} setQuery={setQuery} />
        </div>
      </div>

      {/* EXAM LIST */}
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

export default ExamList;
