import React from "react";
import {
  RadialBarChart,
  RadialBar,
  Legend,
  ResponsiveContainer,
} from "recharts";

const data = [
  { name: "Total", count: 100, fill: "white" },
  { name: "Girls", count: 46, fill: "#fde047" },
  { name: "Boys", count: 54, fill: "#38bdf8" },
];

const CountCard = () => {
  return (
    <div>
      {/* TITLE */}
      <h1 className="text-xl font-bold text-center pt-8">Students</h1>

      {/* CHART CONTAINER (ENSURE HEIGHT IS SET) */}
      <div className="relative w-full h-56 ">
        <ResponsiveContainer width="100%" height="100%">
          <RadialBarChart
            cx="50%"
            cy="50%"
            innerRadius="10%"
            outerRadius="80%"
            barSize={15}
            data={data}
          >
            <RadialBar minAngle={15} background clockWise dataKey="count" />
          </RadialBarChart>
        </ResponsiveContainer>
        <img
          src="https://static.vecteezy.com/system/resources/previews/007/957/718/non_2x/the-female-and-male-toilet-icon-vector.jpg"
          className="w-[40px] h-[40px] absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2"
        />
      </div>

      {/* BOTTOM SECTION */}
      <div className="flex justify-center gap-16 mt-4">
        <div className="flex flex-col items-center gap-1">
          <div className="w-5 h-5 bg-sky-400 rounded-full"></div>
          <h1 className="font-bold">1234</h1>
          <h2 className="text-xs text-gray-600">Boys (55%)</h2>
        </div>
        <div className="flex flex-col items-center gap-1">
          <div className="w-5 h-5 bg-yellow-300 rounded-full"></div>
          <h1 className="font-bold">1245</h1>
          <h2 className="text-xs text-gray-600">Girls (45%)</h2>
        </div>
      </div>
    </div>
  );
};

export default CountCard;
