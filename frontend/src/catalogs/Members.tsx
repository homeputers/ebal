import { useEffect, useState } from 'react';
import { Navigate, Link } from 'react-router-dom';
import { useT } from '../i18n';

interface Member {
  id: number;
  name: string;
  email?: string;
  phone?: string;
  group_ids?: number[];
}

interface Group {
  id: number;
  name: string;
}

export default function Members({ token }: { token: string }) {
  const t = useT();
  const [members, setMembers] = useState<Member[]>([]);
  const [groups, setGroups] = useState<Group[]>([]);
  const [form, setForm] = useState<Omit<Member, 'id'> & { group_ids: number[] }>({ name: '', email: '', phone: '', group_ids: [] });
  const [editing, setEditing] = useState<number | null>(null);
  const [error, setError] = useState('');
  const headers = { 'Authorization': 'Bearer ' + token, 'Content-Type': 'application/json' };

  const load = () => {
    fetch('/api/members', { headers })
      .then(res => res.json())
      .then(setMembers);
    fetch('/api/groups', { headers })
      .then(res => res.json())
      .then(setGroups);
  };

  useEffect(load, [token, headers]);

  const submit = async () => {
    setError("");
    if (!form.name.trim()) { setError(t("Name is required")); return; }
    try {
      const res = await fetch(editing ? `/api/members/${editing}` : "/api/members", {
        method: editing ? "PUT" : "POST",
        headers,
        body: JSON.stringify(form)
      });
      if (!res.ok) {
        const data = await res.json().catch(() => ({}));
        throw new Error(data.message || t("Request failed"));
      }
      setForm({ name: "", email: "", phone: "", group_ids: [] });
      setEditing(null);
      load();
    } catch (err) {
      setError((err as Error).message);
    }
  };

  const edit = (m: Member) => {
    setForm({
      name: m.name,
      email: m.email || '',
      phone: m.phone || '',
      group_ids: m.group_ids || []
    });
    setEditing(m.id);
  };
  const cancel = () => {
    setEditing(null);
    setForm({ name: '', email: '', phone: '', group_ids: [] });
    setError('');
  };
  const remove = async (id: number) => { await fetch(`/api/members/${id}`, { method: "DELETE", headers }); load(); };

  const toggleGroup = (id: number) => {
    setForm(f => ({
      ...f,
      group_ids: f.group_ids.includes(id)
        ? f.group_ids.filter(g => g !== id)
        : [...f.group_ids, id]
    }));
  };
  if (!token) return <Navigate to="/login" />;

  return (
    <div className="container" style={{ maxWidth: '600px' }}>
      <Link to="/dashboard" className="btn btn-link p-0 mb-2">&laquo; {t('Back')}</Link>
      <h2 className="mb-3">{t('Members')}</h2>
      {error && <div className="alert alert-danger">{error}</div>}
      <div className="mb-3">
        <input className="form-control mb-1" placeholder={t('Name')} value={form.name}
          onChange={e => setForm({ ...form, name: (e.target as HTMLInputElement).value })} />
        <input className="form-control mb-1" placeholder={t('Email')} value={form.email}
          onChange={e => setForm({ ...form, email: (e.target as HTMLInputElement).value })} />
        <input className="form-control" placeholder={t('Phone')} value={form.phone}
          onChange={e => setForm({ ...form, phone: (e.target as HTMLInputElement).value })} />
        {groups.map(g => (
          <div className="form-check" key={g.id}>
            <input className="form-check-input" type="checkbox" id={'g'+g.id}
              checked={form.group_ids.includes(g.id)}
              onChange={() => toggleGroup(g.id)} />
            <label className="form-check-label" htmlFor={'g'+g.id}>{g.name}</label>
          </div>
        ))}
        <div className="mt-2">
          <button className="btn btn-primary" onClick={submit}>{editing ? t('Update') : t('Add')}</button>
          {editing && <button className="btn btn-secondary ms-2" onClick={cancel}>{t('Cancel')}</button>}
        </div>
      </div>
      <ul className="list-group">
        {members.map(m => (
          <li key={m.id} className="list-group-item d-flex justify-content-between">
            <span>
              <strong>{m.name}</strong> {m.email} {m.phone}
            </span>
            <span>
              <button className="btn btn-sm btn-secondary me-2" onClick={() => edit(m)}>{t('Edit')}</button>
              <button className="btn btn-sm btn-danger" onClick={() => remove(m.id)}>{t('Delete')}</button>
            </span>
          </li>
        ))}
      </ul>
    </div>
  );
}
