import { Link } from 'react-router-dom';

export default function Home() {
  return (
    <div className="container text-center">
      <h1 className="mb-3">Welcome to Ebal</h1>
      <p className="mb-4">Every Breath and Life portal</p>
      <Link className="btn btn-primary" to="/login">Login</Link>
    </div>
  );
}
