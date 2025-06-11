import { useState } from 'react';
import { BrowserRouter, Routes, Route, Navigate } from 'react-router-dom';
import useTheme from './useTheme';
import Home from './Home';
import Login from './Login';
import Dashboard from './Dashboard';
import Layout from './Layout';
import Groups from './catalogs/Groups';
import Members from './catalogs/Members';
import LineupTemplates from './catalogs/LineupTemplates';
import LineupTemplateGroups from './catalogs/LineupTemplateGroups';
import SongCategories from './catalogs/SongCategories';
import Songs from './catalogs/Songs';

export default function App() {
  useTheme();
  const [token, setToken] = useState(localStorage.getItem('token') || '');

  const handleLogout = () => {
    setToken('');
    localStorage.removeItem('token');
  };

  return (
    <BrowserRouter>
      <Routes>
        <Route path="/" element={<Home />} />
        <Route path="/login" element={<Login setToken={setToken} />} />

        {/* Routes that use the Layout component */}
        <Route element={<Layout onLogout={handleLogout} />}>
          <Route path="/dashboard" element={<Dashboard token={token} />} />
          <Route path="/groups" element={<Groups token={token} />} />
          <Route path="/members" element={<Members token={token} />} />
          <Route path="/lineup-templates" element={<LineupTemplates token={token} />} />
          <Route path="/lineup-templates/:id/groups" element={<LineupTemplateGroups token={token} />} />
          <Route path="/song-categories" element={<SongCategories token={token} />} />
          <Route path="/songs" element={<Songs token={token} />} />
        </Route>

        <Route path="*" element={<Navigate to="/" />} />
      </Routes>
    </BrowserRouter>
  );
}
