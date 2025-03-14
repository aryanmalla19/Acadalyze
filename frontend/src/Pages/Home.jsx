import React from "react";
import Header from "../Components/Common/Header";
import UserCard from "./UserCard";

const Home = () => {
  return (
    <div className="p-4 flex gap-4 flex-col md:flex-row">
      {/* LEFT */}
      <div className="w-full lg:w-2/3">
        <div className="p-4 flex justify-between gap-4">
          <UserCard numbers="1215" type="Students" />
          <UserCard numbers="34351" type="Teachers" />
          <UserCard numbers="5415" type="Parents" />
        </div>
      </div>
      {/* RIGHT */}
      <div className="w-full lg:w-1/3 bg-green-300">right</div>
    </div>
  );
};

export default Home;
