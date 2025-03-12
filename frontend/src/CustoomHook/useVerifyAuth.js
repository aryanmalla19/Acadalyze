import { useState, useEffect, useContext } from "react";
import { AuthContext } from "../Context/AuthContext";
import { checkAuth } from "../Api/Api";

const useVerifyAuth = () => {
  const [isCheckingAuth, setIsCheckingAuth] = useState(true);
  const { authUser, setAuthUser } = useContext(AuthContext);

  useEffect(() => {
    const fetchAuthStatus = async () => {
      try {
        const user = await checkAuth();

        setAuthUser(user.data.role);
      } catch (error) {
        console.log("Authentication failed", error);
        setAuthUser(null);
      } finally {
        setIsCheckingAuth(false);
      }
    };

    fetchAuthStatus();
  }, [setAuthUser]);

  return { authUser, isCheckingAuth };
};

export default useVerifyAuth;
