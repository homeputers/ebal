import { useEffect, useState } from 'react';
import { Navigate, Link, useParams } from 'react-router-dom';

interface Group { id: number; name: string; }
interface TemplateGroup { group_id: number; count: number; group: Group; }
interface Template { id: number; name: string; }

export default function LineupTemplateGroups({ token }: { token: string }) {
  const { id } = useParams();
  const [template, setTemplate] = useState<Template | null>(null);
  const [groups, setGroups] = useState<Group[]>([]);
  const [templateGroups, setTemplateGroups] = useState<TemplateGroup[]>([]);
  const [form, setForm] = useState<{ group_id: number; count: number }>({ group_id: 0, count: 1 });
  const [editing, setEditing] = useState<number | null>(null);
  const [error, setError] = useState('');
  const headers = { 'Authorization': 'Bearer ' + token, 'Content-Type': 'application/json' };

  const load = () => {
    if (!id) return;
    fetch('/api/lineup-templates/' + id, { headers }).then(res => res.json()).then(setTemplate);
    fetch('/api/groups', { headers }).then(res => res.json()).then(setGroups);
    fetch(`/api/lineup-templates/${id}/groups`, { headers }).then(res => res.json()).then(setTemplateGroups);
  };
  useEffect(load, [id, headers]);

  const submit = async () => {
    setError('');
    if (!form.group_id) { setError('Group is required'); return; }
    try {
      const res = await fetch(editing ?
        `/api/lineup-templates/${id}/groups/${editing}` :
        `/api/lineup-templates/${id}/groups`, {
        method: editing ? 'PUT' : 'POST',
        headers,
        body: JSON.stringify(form)
      });
      if (!res.ok) {
        const data = await res.json().catch(() => ({}));
        throw new Error(data.message || 'Request failed');
      }
      setForm({ group_id: 0, count: 1 });
      setEditing(null);
      load();
    } catch (err: any) {
      setError(err.message);
    }
  };

  const edit = (tg: TemplateGroup) => { setForm({ group_id: tg.group_id, count: tg.count }); setEditing(tg.group_id); };
  const cancel = () => { setEditing(null); setForm({ group_id: 0, count: 1 }); setError(''); };
  const remove = async (group_id: number) => { 
    if (window.confirm('Are you sure you want to delete this group?')) {
      await fetch(`/api/lineup-templates/${id}/groups/${group_id}`, { method: 'DELETE', headers }); 
      load(); 
    }
  };

  if (!token) return <Navigate to="/login" />;

  return (
    <div className="container" style={{ maxWidth: '600px' }}>
      <Link to="/lineup-templates" className="btn btn-link p-0 mb-2">&laquo; Back</Link>
      <h2 className="mb-3">Template: {template?.name}</h2>
      {error && <div className="alert alert-danger">{error}</div>}
      <div className="mb-3">
        <select className="form-select mb-1" value={form.group_id}
          onChange={e => setForm({ ...form, group_id: +(e.target as HTMLSelectElement).value })}>
          <option value={0}>Select group</option>
          {groups.map(g => <option key={g.id} value={g.id}>{g.name}</option>)}
        </select>
        <input type="number" min="1" className="form-control" placeholder="Count" value={form.count}
          onChange={e => setForm({ ...form, count: +(e.target as HTMLInputElement).value })} />
        <div className="mt-2">
          <button className="btn btn-primary" onClick={submit}>{editing ? 'Update' : 'Add'}</button>
          {editing && <button className="btn btn-secondary ms-2" onClick={cancel}>Cancel</button>}
        </div>
      </div>
      <ul className="list-group">
        {templateGroups.map(tg => (
          <li key={tg.group_id} className="list-group-item d-flex justify-content-between">
            <span>{tg.group.name} - {tg.count}</span>
            <span>
              <button className="btn btn-sm btn-secondary me-2" onClick={() => edit(tg)}>Edit</button>
              <button className="btn btn-sm btn-danger" onClick={() => remove(tg.group_id)}>Delete</button>
            </span>
          </li>
        ))}
      </ul>
    </div>
  );
}
