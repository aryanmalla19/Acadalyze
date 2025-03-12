import React from "react";

const CredentialsOptions = ({ isLoggedIn }) => {
  return (
    <>
      {isLoggedIn && (
        <div className="flex gap-16 mb-4 text-sm text-gray-600 font-semibold">
          <label className="flex items-center">
            <input type="checkbox" className="mr-2" />
            Remember me
          </label>
          <a href="#" className="text-red-500 hover:text-red-600">
            Forgot Password?
          </a>
        </div>
      )}
    </>
  );
};

export default CredentialsOptions;
