import React from 'react';
import './ErrorMessage.css';

const ErrorMessage = ({ message }) => {
  return (
    <div className="error-message">
      <h3>Error Loading Data</h3>
      <p>{message}</p>
      <button onClick={() => window.location.reload()}>Try Again</button>
    </div>
  );
};

export default ErrorMessage;
