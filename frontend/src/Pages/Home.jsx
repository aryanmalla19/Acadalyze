import React from "react";

import Header from "../Components/Common/Header";

const Home = () => {
  return (
    <div className="flex-1 overflow-auto relative z-10">
      <Header title="Home" />

      <main className="max-w-7xl mx-auto py-6 px-4 lg:px-8"></main>
    </div>
  );
};

export default Home;
