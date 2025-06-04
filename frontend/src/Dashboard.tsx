declare const React: any;
declare const ReactRouterDOM: any;

const { Navigate } = ReactRouterDOM;

interface DashboardProps {
  token: string;
}

export default function Dashboard({ token }: DashboardProps) {
  const [message, setMessage] = React.useState('');
  const [user, setUser] = React.useState('');

  React.useEffect(() => {
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
