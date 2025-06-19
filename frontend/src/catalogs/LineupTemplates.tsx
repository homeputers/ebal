import { useEffect, useState } from 'react';
import { Navigate, Link } from 'react-router-dom';
import { useT } from '../i18n';

interface Template { id: number; name: string; }

export default function LineupTemplates({ token }: { token: string }) {
  const t = useT();
  const [templates, setTemplates] = useState<Template[]>([]);
  const [form, setForm] = useState<Omit<Template, 'id'>>({ name: '' });
  const [editing, setEditing] = useState<number | null>(null);
  const [error, setError] = useState('');
  const headers = { 'Authorization': 'Bearer ' + token, 'Content-Type': 'application/json' };

  const load = () => {
    fetch('/api/lineup-templates', { headers })
      .then(res => res.json())
      .then(setTemplates);
  };
  useEffect(load, []);

  const submit = async () => {
    setError('');
    if (!form.name.trim()) { setError(t('Name is required')); return; }
    try {
      const res = await fetch(editing ? '/api/lineup-templates/' + editing : '/api/lineup-templates', {
        method: editing ? 'PUT' : 'POST',
        headers,
        body: JSON.stringify(form)
      });
      if (!res.ok) {
        const data = await res.json().catch(() => ({}));
        throw new Error(data.message || t('Request failed'));
      }
      setForm({ name: '' });
      setEditing(null);
      load();
    } catch (err: any) {
      setError(err.message);
    }
  };

  const edit = (t: Template) => { setForm({ name: t.name }); setEditing(t.id); };
  const cancel = () => { setEditing(null); setForm({ name: '' }); setError(''); };
  const remove = async (id: number) => { await fetch('/api/lineup-templates/' + id, { method: 'DELETE', headers }); load(); };

  if (!token) return <Navigate to="/login" />;

  return (
    <div className="container" style={{ maxWidth: '600px' }}>
      <Link to="/dashboard" className="btn btn-link p-0 mb-2">&laquo; {t('Back')}</Link>
      <h2 className="mb-3">{t('Lineup Templates')}</h2>
      {error && <div className="alert alert-danger">{error}</div>}
      <div className="mb-3">
        <input className="form-control" placeholder={t('Name')} value={form.name}
          onChange={e => setForm({ name: (e.target as HTMLInputElement).value })} />
        <div className="mt-2">
          <button className="btn btn-primary" onClick={submit}>{editing ? t('Update') : t('Add')}</button>
          {editing && <button className="btn btn-secondary ms-2" onClick={cancel}>{t('Cancel')}</button>}
        </div>
      </div>
      <ul className="list-group">
        {templates.map(t => (
          <li key={t.id} className="list-group-item d-flex justify-content-between">
            <span>{t.name}</span>
            <span>
              <Link to={`/lineup-templates/${t.id}/groups`} className="btn btn-sm btn-outline-primary me-2">{t('Groups')}</Link>
              <button className="btn btn-sm btn-secondary me-2" onClick={() => edit(t)}>{t('Edit')}</button>
              <button className="btn btn-sm btn-danger" onClick={() => remove(t.id)}>{t('Delete')}</button>
            </span>
          </li>
        ))}
      </ul>
    </div>
  );
}
