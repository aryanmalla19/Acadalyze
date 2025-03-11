import axios from "axios";

const API_URL = "http://localhost:8080/api";

// Register API call
export const register = async (formData) => {
  try {
    const response = await axios.post(`${API_URL}/register`, formData, {
      headers: {
        "Content-Type": "application/json",
      },
    });
    return response.data;
  } catch (error) {
    throw new Error(error.response?.data?.message || "Registration failed");
  }
};

// Login API call
export const login = async (identifier, password) => {
  try {
    const response = await axios.post(`${API_URL}/login`, {
      identifier,
      password,
    });
    return response.data;
  } catch (error) {
    throw new Error(error.response?.data?.message || "Login failed");
  }
};
