import { createContext, useContext, useState, ReactNode } from 'react';

export type Lang = 'en' | 'es';

const translations: Record<Lang, Record<string, string>> = {
  en: {
    'Logout': 'Logout',
    'Welcome to Ebal': 'Welcome to Ebal',
    'Every Breath and Life portal': 'Every Breath and Life portal',
    'Login': 'Login',
    'Username': 'Username',
    'Password': 'Password',
    'Invalid credentials': 'Invalid credentials',
    'Login failed': 'Login failed',
    'Hello': 'Hello',
    'Groups': 'Groups',
    'Members': 'Members',
    'Lineup Templates': 'Lineup Templates',
    'Song Categories': 'Song Categories',
    'Songs': 'Songs',
    'Name is required': 'Name is required',
    'Title is required': 'Title is required',
    'Name': 'Name',
    'Description': 'Description',
    'Update': 'Update',
    'Add': 'Add',
    'Cancel': 'Cancel',
    'Edit': 'Edit',
    'Delete': 'Delete',
    'Email': 'Email',
    'Phone': 'Phone',
    'Title': 'Title',
    'No category': 'No category',
    'Original Key': 'Original Key',
    'Original Author': 'Original Author',
    'Back': 'Back',
    'Request failed': 'Request failed'
  },
  es: {
    'Logout': 'Cerrar sesión',
    'Welcome to Ebal': 'Bienvenido a Ebal',
    'Every Breath and Life portal': 'Portal de Every Breath and Life',
    'Login': 'Iniciar sesión',
    'Username': 'Usuario',
    'Password': 'Contraseña',
    'Invalid credentials': 'Credenciales inválidas',
    'Login failed': 'Error al iniciar sesión',
    'Hello': 'Hola',
    'Groups': 'Grupos',
    'Members': 'Miembros',
    'Lineup Templates': 'Plantillas de alineación',
    'Song Categories': 'Categorías de canciones',
    'Songs': 'Canciones',
    'Name is required': 'Nombre requerido',
    'Title is required': 'Título requerido',
    'Name': 'Nombre',
    'Description': 'Descripción',
    'Update': 'Actualizar',
    'Add': 'Agregar',
    'Cancel': 'Cancelar',
    'Edit': 'Editar',
    'Delete': 'Eliminar',
    'Email': 'Correo',
    'Phone': 'Teléfono',
    'Title': 'Título',
    'No category': 'Sin categoría',
    'Original Key': 'Tono original',
    'Original Author': 'Autor original',
    'Back': 'Volver',
    'Request failed': 'Error de solicitud'
  }
};

interface I18nContextProps {
  lang: Lang;
  setLang: (l: Lang) => void;
}

const I18nContext = createContext<I18nContextProps>({ lang: 'en', setLang: () => {} });

export function I18nProvider({ children }: { children: ReactNode }) {
  const [lang, setLangState] = useState<Lang>(() => {
    const stored = localStorage.getItem('lang');
    return stored === 'es' ? 'es' : 'en';
  });
  const setLang = (l: Lang) => {
    localStorage.setItem('lang', l);
    setLangState(l);
  };
  return (
    <I18nContext.Provider value={{ lang, setLang }}>
      {children}
    </I18nContext.Provider>
  );
}

export function useI18n() {
  return useContext(I18nContext);
}

export function useT() {
  const { lang } = useI18n();
  return (key: string) => translations[lang][key] || key;
}
