
import React from "react";

function FeedbackList({ feedbacks }) {
  return (
    <div>
      <h2>Feedback Dashboard</h2>

      {feedbacks.length === 0 ? (
        <p>No feedback yet</p>
      ) : (
        <ul>
          {feedbacks.map((item, index) => (
            <li key={index}>
              <h3>{item.name}</h3>
              <p>Email: {item.email}</p>
              <p>PRN: {item.prn}</p>
              <p>Course: {item.course}</p>
              <p>Message: {item.message}</p>
            </li>
          ))}
        </ul>
      )}
    </div>
  );
}

export default FeedbackList;