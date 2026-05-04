import React, { useState } from "react";
import FeedbackForm from "./FeedbackForm";
import FeedbackList from "./FeedbackList";

function App() {
  const [feedbacks, setFeedbacks] = useState([]);

  const addFeedback = (data) => {
    setFeedbacks([...feedbacks, data]);
  };

  return (
    <div>
      <FeedbackForm addFeedback={addFeedback} />
      <FeedbackList feedbacks={feedbacks} />
    </div>
  );
}

export default App;