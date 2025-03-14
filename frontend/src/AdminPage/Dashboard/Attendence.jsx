import React from "react";
import {
  BarChart,
  Bar,
  Rectangle,
  XAxis,
  YAxis,
  CartesianGrid,
  Tooltip,
  Legend,
  ResponsiveContainer,
} from "recharts";

const data = [
  { name: "Sun", present: 90, absent: 10 },
  { name: "Mon", present: 85, absent: 15 },
  { name: "Tue", present: 80, absent: 20 },
  { name: "Wed", present: 95, absent: 5 },
  { name: "Thu", present: 70, absent: 30 },
  { name: "Fri", present: 88, absent: 12 },
];

// Class Component with Bar Chart
const Attendance = () => {
  return (
    <div style={{ width: "100%", height: 400 }}>
      <ResponsiveContainer width="100%" height="100%">
        <BarChart
          data={data}
          margin={{ top: 5, right: 30, left: 20, bottom: 5 }}
        >
          <CartesianGrid strokeDasharray="3 3" />
          <XAxis dataKey="name" interval={0} />
          <YAxis />
          <Tooltip />
          <Legend />
          <Bar
            dataKey="present"
            fill="#82ca9d"
            activeBar={<Rectangle fill="green" stroke="darkgreen" />}
          />
          <Bar
            dataKey="absent"
            fill="#ff6961"
            activeBar={<Rectangle fill="red" stroke="darkred" />}
          />
        </BarChart>
      </ResponsiveContainer>
    </div>
  );
};
export default Attendance;
