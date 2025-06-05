import { useState, useEffect } from 'react';
import { Navigate, Link } from 'react-router-dom';

interface DashboardProps {
  token: string;
}

export default function Dashboard({ token }: DashboardProps) {
  const [message, setMessage] = useState('');
  const [user, setUser] = useState('');

  useEffect(() => {
    if (!token) return;
      fetch('/api/dashboard', { headers: { 'Authorization': 'Bearer ' + token } })
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
      <div className="d-flex flex-column align-items-center mt-4" style={{gap:'0.5rem'}}>
        <Link className="btn btn-outline-primary" to="/groups">Groups</Link>
        <Link className="btn btn-outline-primary" to="/members">Members</Link>
        <Link className="btn btn-outline-primary" to="/lineup-templates">Lineup Templates</Link>
        <Link className="btn btn-outline-primary" to="/song-categories">Song Categories</Link>
        <Link className="btn btn-outline-primary" to="/songs">Songs</Link>
      </div>
    </div>
  );
}
