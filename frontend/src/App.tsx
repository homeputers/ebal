declare const React: any;
declare const ReactDOM: any;
declare const ReactRouterDOM: any;

import useTheme from './useTheme';
import Home from './Home';
import Login from './Login';
import Dashboard from './Dashboard';

const { BrowserRouter, Routes, Route, Navigate } = ReactRouterDOM;

export default function App() {
  useTheme();
  const [token, setToken] = React.useState(localStorage.getItem('token') || '');

  return (
    <BrowserRouter>
      <Routes>
        <Route path="/" element={<Home />} />
        <Route path="/login" element={<Login setToken={setToken} />} />
        <Route path="/dashboard" element={<Dashboard token={token} />} />
        <Route path="*" element={<Navigate to="/" />} />
      </Routes>
    </BrowserRouter>
  );
}

ReactDOM.createRoot(document.getElementById('root')!).render(<App />);
