import axios from "axios";

const axiosInstance = axios.create({
  baseURL: "http://localhost:8080/api/auth",
  withCredentials: true,
});

export const register = async (data) => {
  try {
    const response = await axiosInstance.post("/register", data);
    console.log(response.data);
    return response.data;
  } catch (error) {
    console.error("Validation Errors:", error.response.data.errors);
    throw new Error(error.response?.data?.message || "Registration failed");
  }
};

export const checkAuth = async () => {
  try {
    const response = axiosInstance.get("/verify");
    console.log(response.data, "api.js");
    return response.data;
  } catch (error) {
    console.log("Check Authentication Errors:", error.response.data.errors);
    throw new Error(error.response?.data?.message || "Authentication failed");
  }
};
