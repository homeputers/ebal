import { useState, useEffect } from 'react';
import { Navigate } from 'react-router-dom';

interface DashboardProps {
  token: string;
}

export default function Dashboard({ token }: DashboardProps) {
  const [message, setMessage] = useState('');
  const [user, setUser] = useState('');

  useEffect(() => {
    if (!token) return;
    fetch('/dashboard', { headers: { 'Authorization': 'Bearer ' + token } })
      .then(res => res.json())
      .then(data => {
        setMessage(data.message);
        setUser(data.user);
      });
  }, [token]);

  if (!token) return <Navigate to="/login" />;

  return (
    <div className="container text-center">
      <h2 className="mb-3">{message}</h2>
      <p>Hello {user}</p>
    </div>
  );
}
