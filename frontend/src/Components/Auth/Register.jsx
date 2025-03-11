import {
  FaEnvelope,
  FaUser,
  FaLock,
  FaPhone,
  FaCalendar,
} from "react-icons/fa";
import InputField from "../Auth/InputField";

const Register = ({ formData, handleChange, handleSubmit }) => {
  return (
    <form
      className="w-full max-w-[700px] grid grid-cols-1 md:grid-cols-2 gap-4"
      onSubmit={handleSubmit}
    >
      {/* Left Side */}
      <div className="flex flex-col ">
        <InputField
          icon={FaEnvelope}
          type="email"
          placeholder="Email"
          name="email"
          value={formData.email}
          onChange={handleChange}
        />
        <InputField
          icon={FaUser}
          type="text"
          placeholder="Username"
          name="username"
          value={formData.username}
          onChange={handleChange}
        />
        <InputField
          icon={FaUser}
          type="text"
          placeholder="First Name"
          name="first_name"
          value={formData.first_name}
          onChange={handleChange}
        />
        <InputField
          icon={FaUser}
          type="text"
          placeholder="Last Name"
          name="last_name"
          value={formData.last_name}
          onChange={handleChange}
        />
      </div>

      {/* Right Side (4 Fields) */}
      <div className="flex flex-col">
        <InputField
          icon={FaLock}
          type="password"
          placeholder="Password"
          name="password"
          value={formData.password}
          onChange={handleChange}
        />
        <InputField
          type="text"
          placeholder="Address"
          name="address"
          value={formData.address}
          onChange={handleChange}
        />
        <InputField
          icon={FaPhone}
          type="text"
          placeholder="Phone Number"
          name="phone_number"
          value={formData.phone_number}
          onChange={handleChange}
        />
        <InputField
          icon={FaPhone}
          type="text"
          placeholder="Parent's Phone Number"
          name="parent_phone_number"
          value={formData.parent_phone_number}
          onChange={handleChange}
        />
      </div>

      {/* Full Width Field */}
      <div className="col-span-1 md:col-span-2">
        <InputField
          icon={FaCalendar}
          type="date"
          placeholder="Date of Birth"
          name="date_of_birth"
          value={formData.date_of_birth}
          onChange={handleChange}
        />
      </div>

      {/* Submit Button */}
      <div className="col-span-1 md:col-span-2">
        <button
          type="submit"
          className="w-full bg-[#7494ec] text-white py-2 rounded-md mb-2 hover:bg-blue-600 transition font-semibold"
        >
          Register
        </button>
      </div>
    </form>
  );
};

export default Register;
