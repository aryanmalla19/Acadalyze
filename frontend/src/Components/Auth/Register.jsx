import { useForm } from "react-hook-form";

import InputField from "../Auth/InputField";
import { leftFields, rightFields } from "../Fields/Fields";

import { FaCalendar } from "react-icons/fa";
import { AuthButton } from "../Common/buttons";

const Register = ({ isLoggedIn }) => {
  const {
    register,
    handleSubmit,
    formState: { errors, isSubmitting },
    reset,
  } = useForm();

  const onSubmit = (data) => {
    console.log(data);
    reset();
  };

  return (
    <form
      className="w-full max-w-[700px] grid grid-cols-1 md:grid-cols-2 gap-4"
      onSubmit={handleSubmit(onSubmit)}
    >
      {/* Left Fields */}
      <div className="flex flex-col gap-1.5">
        {leftFields.map((field) => (
          <InputField
            key={field.name}
            icon={field.icon}
            type={field.type}
            placeholder={field.placeholder}
            name={field.name}
            autocomplete={field.name}
            validation={field.validation}
            register={register}
            errors={errors[field.name]}
          />
        ))}
      </div>

      {/* Right Fields */}
      <div className="flex flex-col gap-1.5">
        {rightFields.map((field) => (
          <InputField
            key={field.name}
            icon={field.icon}
            type={field.type}
            placeholder={field.placeholder}
            name={field.name}
            autocomplete={field.name}
            validation={field.validation}
            register={register}
            errors={errors[field.name]}
          />
        ))}
      </div>

      {/* Full Width Field */}
      <div className="col-span-1 md:col-span-2">
        <InputField
          icon={FaCalendar}
          type="date"
          placeholder="Date of Birth"
          name="date_of_birth"
          autocomplete="date_of_birth"
          validation={{
            required: "Date of birth is required",
          }}
          register={register}
          errors={errors.date_of_birth}
        />
      </div>

      {/* Submit Button */}

      <div className="col-span-1 md:col-span-2">
        <AuthButton isLoggedIn={isLoggedIn} isSubmitting={isSubmitting} />
      </div>
    </form>
  );
};

export default Register;

{
  /* <div className="flex flex-col gap-1.5">
        <InputField
          icon={FaLock}
          type="password"
          placeholder="Password"
          name="password"
          autocomplete="password"
          validation={{
            required: "Password is required",
            minLength: {
              value: 6,
              message: "Password must be at least 6 characters",
            },
            maxLength: {
              value: 30,
              message: "Password must be no more than 30 characters",
            },
          }}
          register={register}
          errors={errors.password}
        />

        <InputField
          type="text"
          placeholder="Address"
          name="address"
          autocomplete="address"
          validation={{
            required: "Address is required",
            minLength: {
              value: 3,
              message: "Address must be at least 3 characters",
            },
            maxLength: {
              value: 20,
              message: "Address must be no more than 20 characters",
            },
          }}
          register={register}
          errors={errors.address}
        />

        <InputField
          icon={FaPhone}
          type="text"
          placeholder="Phone Number"
          name="phone_number"
          autocomplete="phone_number"
          validation={{
            required: "Phone number is required",
            minLength: {
              value: 6,
              message: "Phone number must be at least 6 characters",
            },
            maxLength: {
              value: 10,
              message: "Phone number must be no more than 10 characters",
            },
          }}
          register={register}
          errors={errors.phone_number}
        />

        <InputField
          icon={FaPhone}
          type="text"
          placeholder="Parent's Phone Number"
          name="parent_phone_number"
          autocomplete="parent_phone_number"
          validation={{
            required: "Parent phone number is required",
            minLength: {
              value: 6,
              message: "Phone number must be at least 6 characters",
            },
            maxLength: {
              value: 10,
              message: "Phone number must be no more than 10 characters",
            },
          }}
          register={register}
          errors={errors.parent_phone_number}
        />
      </div> */
}

{
  /* <div className="flex flex-col gap-1.5">
        <InputField
          icon={FaEnvelope}
          type="email"
          placeholder="Email"
          name="email"
          autocomplete="email"
          validation={{
            required: "Email is required",
            pattern: {
              value: /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/,
              message: "Please enter a valid email address",
            },
          }}
          register={register}
          errors={errors.email}
        />
        <InputField
          icon={FaUser}
          type="text"
          placeholder="Username"
          name="username"
          autocomplete="username"
          validation={{
            required: "Username is required",
            minLength: {
              value: 6,
              message: "Username must be at least 6 characters",
            },
            maxLength: {
              value: 20,
              message: "Username must be no more than 30 characters",
            },
          }}
          register={register}
          errors={errors.username}
        />
        <InputField
          icon={FaUser}
          type="text"
          placeholder="First Name"
          name="first_name"
          autocomplete="first_name"
          validation={{
            required: "First name is required",
            minLength: {
              value: 2,
              message: "First name must be at least 6 characters",
            },
            maxLength: {
              value: 30,
              message: "First name must be no more than 30 characters",
            },
          }}
          register={register}
          errors={errors.first_name}
        />

        <InputField
          icon={FaUser}
          type="text"
          placeholder="Last Name"
          name="last_name"
          autocomplete="last_name"
          validation={{
            required: "Last name is required",
            minLength: {
              value: 2,
              message: "Last name must be at least 6 characters",
            },
            maxLength: {
              value: 30,
              message: "Last name must be no more than 30 characters",
            },
          }}
          register={register}
          errors={errors.last_name}
        />
      </div> */
}

{
  /* <button
          type="submit"
          className="w-full bg-[#7494ec] text-white py-2 rounded-md mb-2 hover:bg-blue-600 transition font-semibold"
        >
          {isSubmitting ? (
            <>
              <Loader2 className="h-5 w-5 animate-spin" />
              Loading...
            </>
          ) : (
            "Register"
          )}
        </button> */
}
