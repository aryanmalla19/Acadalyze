// fields/fields.js
import { FaEnvelope, FaUser, FaLock, FaPhone } from "react-icons/fa";

export const leftFields = [
  {
    icon: FaEnvelope,
    name: "email",
    type: "email",
    placeholder: "Email",
    validation: {
      required: "Email is required",
      pattern: {
        value: /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/,
        message: "Please enter a valid email address",
      },
    },
  },
  {
    icon: FaUser,
    name: "username",
    type: "text",
    placeholder: "Username",
    validation: {
      required: "Username is required",
      minLength: {
        value: 6,
        message: "Username must be at least 6 characters",
      },
      maxLength: {
        value: 20,
        message: "Username must be no more than 20 characters",
      },
    },
  },
  {
    icon: FaUser,
    name: "first_name",
    type: "text",
    placeholder: "First Name",
    validation: {
      required: "First name is required",
      minLength: {
        value: 2,
        message: "First name must be at least 2 characters",
      },
      maxLength: {
        value: 30,
        message: "First name must be no more than 30 characters",
      },
    },
  },
  {
    icon: FaUser,
    name: "last_name",
    type: "text",
    placeholder: "Last Name",
    validation: {
      required: "Last name is required",
      minLength: {
        value: 2,
        message: "Last name must be at least 2 characters",
      },
      maxLength: {
        value: 30,
        message: "Last name must be no more than 30 characters",
      },
    },
  },
];

export const rightFields = [
  {
    icon: FaLock,
    name: "password",
    type: "password",
    placeholder: "Password",
    validation: {
      required: "Password is required",
      minLength: {
        value: 6,
        message: "Password must be at least 6 characters",
      },
      maxLength: {
        value: 30,
        message: "Password must be no more than 30 characters",
      },
    },
  },
  {
    name: "address",
    type: "text",
    placeholder: "Address",
    validation: {
      required: "Address is required",
      minLength: {
        value: 3,
        message: "Address must be at least 3 characters",
      },
      maxLength: {
        value: 20,
        message: "Address must be no more than 20 characters",
      },
    },
  },
  {
    icon: FaPhone,
    name: "phone_number",
    type: "text",
    placeholder: "Phone Number",
    validation: {
      required: "Phone number is required",
      minLength: {
        value: 6,
        message: "Phone number must be at least 6 characters",
      },
      maxLength: {
        value: 10,
        message: "Phone number must be no more than 10 characters",
      },
    },
  },
  {
    icon: FaPhone,
    name: "parent_phone_number",
    type: "text",
    placeholder: "Parent's Phone Number",
    validation: {
      required: "Parent phone number is required",
      minLength: {
        value: 6,
        message: "Phone number must be at least 6 characters",
      },
      maxLength: {
        value: 10,
        message: "Phone number must be no more than 10 characters",
      },
    },
  },
];
