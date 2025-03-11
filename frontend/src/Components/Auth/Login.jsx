import { FaUser, FaLock } from "react-icons/fa";
import InputField from "../Auth/InputField";

const Login = ({ formData, handleChange, handleSubmit }) => {
  return (
    <form className="w-full max-w-[300px]" onSubmit={handleSubmit}>
      <InputField
        icon={FaUser}
        type="text"
        placeholder="Username or Email"
        name="identifier" // Ensure this matches the key in formData
        value={formData.identifier}
        onChange={handleChange}
        autocomplete="username"
      />

      <InputField
        icon={FaLock}
        type="password"
        placeholder="Password"
        name="password" // Ensure this matches the key in formData
        value={formData.password}
        onChange={handleChange}
        autocomplete="current-password"
      />

      <button
        type="submit"
        className="w-full bg-[#7494ec] text-white py-2 rounded-md mb-4 hover:bg-blue-600 transition font-semibold"
      >
        Login
      </button>
    </form>
  );
};

export default Login;
