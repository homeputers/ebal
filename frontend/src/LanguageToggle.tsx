import { useI18n } from './i18n';

export default function LanguageToggle() {
  const { lang, setLang } = useI18n();
  return (
    <select
      className="form-select form-select-sm"
      style={{ width: 'auto' }}
      value={lang}
      onChange={e => setLang(e.target.value as 'en' | 'es')}
    >
      <option value="en">EN</option>
      <option value="es">ES</option>
    </select>
  );
}
