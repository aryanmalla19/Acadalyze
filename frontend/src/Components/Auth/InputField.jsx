import React from "react";

const InputField = ({ icon: Icon, type, placeholder }) => {
  return (
    <div className="mb-4">
      <div className="flex items-center bg-gray-100 rounded-md p-2">
        <input
          type={type}
          placeholder={placeholder}
          required
          className="w-full bg-transparent focus:outline-none"
        />
        {Icon && <Icon className="text-gray-500 mr-2" />}
      </div>
    </div>
  );
};

export default InputField;
