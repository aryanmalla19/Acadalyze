import React from "react";

const InputField = ({
  icon: Icon,
  type,
  placeholder,
  name,
  register,
  validation,
  autocomplete,
  errors,
}) => {
  return (
    <div className="mb-4 relative">
      <div className="flex items-center bg-gray-100 rounded-md p-2">
        <input
          type={type}
          placeholder={placeholder}
          name={name}
          autoComplete={autocomplete}
          {...register(name, validation)}
          className="w-full bg-transparent focus:outline-none"
        />
        {Icon && <Icon className="text-gray-500 mr-2" />}
      </div>
      {errors && (
        <p className="text-[10px] text-red-500 absolute -bottom-3.5">
          *{errors.message}
        </p>
      )}
    </div>
  );
};

export default InputField;
