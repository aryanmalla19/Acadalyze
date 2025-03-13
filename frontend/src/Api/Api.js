import axios from "axios";

const axiosInstance = axios.create({
  baseURL: "http://localhost:8080/api/auth",
  withCredentials: true,
});

export const login = async (data) => {
  try {
    const response = await axiosInstance.post("/login", data);
    console.log(response.data, "api.js login");
    return response.data;
  } catch (error) {
    console.error("Validation Errors:", error.response.data.errors);
    throw new Error(error.response?.data?.message || "Login failed");
  }
};

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
    const response = await axiosInstance.get("/verify");
    console.log(response);
    return response.data;
  } catch (error) {
    console.log("Check Authentication Errors:", error.response.data.errors);
    throw new Error(error.response?.data?.message || "Authentication failed");
  }
};

export const logout = async () => {
  try {
    const response = await axiosInstance.post("/logout");
    console.log(response.data, "api.js logout");
    return response.data;
  } catch (error) {
    console.error("Lgout Errors:", error.response.data.errors);
    throw new Error(error.response?.data?.message || "Logout failed");
  }
};
