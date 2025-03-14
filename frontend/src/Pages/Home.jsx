import React from "react";
import Header from "../Components/Common/Header";
import UserCard from "./UserCard";
import CountCard from "./CountCard";

const Home = () => {
  return (
    <div className="p-4 flex gap-4 flex-col md:flex-row">
      {/* LEFT */}
      <div className="w-full lg:w-2/3">
        <div className="p-4 flex justify-between gap-4 flex-col lg:flex-row">
          <UserCard numbers="1215" type="Students" />
          <UserCard numbers="34351" type="Teachers" />
          <UserCard numbers="5415" type="Parents" />
        </div>

        {/* Middle Charts */}
        <div className="flex flex-col lg:flex-row gap-4 ml-5 mt-5">
          {/* Count Charts */}
          <div className="w-full lg:w-1/3 h-[400px] bg-white">
            <CountCard />
          </div>
          {/* Attendence Charts */}
          <div className="w-full lg:w-2/3 bg-white">{/* <CountCard /> */}</div>
        </div>
      </div>

      {/* RIGHT */}
      <div className="w-full lg:w-1/3 bg-green-300">right</div>
    </div>
  );
};

export default Home;
