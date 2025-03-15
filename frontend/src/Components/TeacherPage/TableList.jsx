import React from "react";
import { Trash, Edit } from "lucide-react"; // Import icons

const TableList = ({ columns, data, onDelete, onUpdate }) => {
  return (
    <div>
      <table className="w-full mt-8 border-collapse border border-gray-300 ">
        <thead>
          <tr className="text-left bg-gray-700 text-white">
            {columns.map((col) => (
              <th
                key={col.accessor}
                className="border border-gray-300 px-4 py-2"
              >
                {col.header}
              </th>
            ))}
          </tr>
        </thead>
        <tbody>
          {data.map((row, index) => (
            <tr key={index} className="border border-gray-300">
              {columns.map((col) => (
                <td
                  key={col.accessor}
                  className="border border-gray-300 px-4 py-2"
                >
                  {col.accessor === "actions" ? (
                    <div className="flex space-x-4">
                      <button
                        onClick={() => onUpdate(row.teacherid)}
                        className="text-blue-500 hover:text-blue-700"
                      >
                        <Edit size={20} />
                      </button>
                      <button
                        onClick={() => onDelete(row.teacherid)}
                        className="text-red-500 hover:text-red-700"
                      >
                        <Trash size={20} />
                      </button>
                    </div>
                  ) : (
                    row[col.accessor] || "-"
                  )}
                </td>
              ))}
            </tr>
          ))}
        </tbody>
      </table>
    </div>
  );
};

export default TableList;
