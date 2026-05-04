import React, { useState, useRef, useEffect } from "react";
import "./FeedbackForm.css";

function FeedbackForm({ addFeedback }) {
  const [formData, setFormData] = useState({
    name: "",
    email: "",
    prn: "",
    course: "",
    message: ""
  });

  const [errors, setErrors] = useState({});
  const nameRef = useRef();

  useEffect(() => {
    nameRef.current.focus();
  }, []);

  const handleChange = (e) => {
    setFormData({
      ...formData,
      [e.target.name]: e.target.value
    });
  };

  const validate = () => {
    let tempErrors = {};

    if (!formData.name) tempErrors.name = "Name is required";
    if (!formData.email.includes("@")) tempErrors.email = "Valid email required";
    if (!formData.prn) tempErrors.prn = "PRN required";
    if (!formData.course) tempErrors.course = "Course required";
    if (!formData.message) tempErrors.message = "Feedback required";

    setErrors(tempErrors);
    return Object.keys(tempErrors).length === 0;
  };

  const handleSubmit = (e) => {
    e.preventDefault();

    if (validate()) {
      addFeedback(formData);

      setFormData({
        name: "",
        email: "",
        prn: "",
        course: "",
        message: ""
      });

      setErrors({});
      nameRef.current.focus();
    }
  };

  return (
    <div className="form-container">
      <form onSubmit={handleSubmit} className="form-card">
        <h2>Student Feedback</h2>

        <div className="form-group">
          <input
            ref={nameRef}
            type="text"
            name="name"
            placeholder="Full Name"
            value={formData.name}
            onChange={handleChange}
          />
          {errors.name && <span>{errors.name}</span>}
        </div>

        <div className="form-group">
          <input
            type="email"
            name="email"
            placeholder="Email Address"
            value={formData.email}
            onChange={handleChange}
          />
          {errors.email && <span>{errors.email}</span>}
        </div>

        <div className="form-group">
          <input
            type="text"
            name="prn"
            placeholder="PRN Number"
            value={formData.prn}
            onChange={handleChange}
          />
          {errors.prn && <span>{errors.prn}</span>}
        </div>

        <div className="form-group">
          <input
            type="text"
            name="course"
            placeholder="Course Name"
            value={formData.course}
            onChange={handleChange}
          />
          {errors.course && <span>{errors.course}</span>}
        </div>

        <div className="form-group">
          <textarea
            name="message"
            placeholder="Write your feedback..."
            value={formData.message}
            onChange={handleChange}
          />
          {errors.message && <span>{errors.message}</span>}
        </div>

        <button type="submit">Submit Feedback</button>
      </form>
    </div>
  );
}

export default FeedbackForm;