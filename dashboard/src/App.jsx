import { Routes, Route, Navigate } from 'react-router-dom';
import Login from './pages/Login';
import DashboardLayout from './components/DashboardLayout';
import Overview from './pages/Overview';
import Reports from './pages/Reports';
import MapView from './pages/MapView';
import Priority from './pages/Priority';
import CostEstimation from './pages/CostEstimation';
import Statistics from './pages/Statistics';

function PrivateRoute({ children }) {
  const token = localStorage.getItem('fixla_token');
  return token ? children : <Navigate to="/login" />;
}

export default function App() {
  return (
    <Routes>
      <Route path="/login" element={<Login />} />
      <Route
        path="/"
        element={
          <PrivateRoute>
            <DashboardLayout />
          </PrivateRoute>
        }
      >
        <Route index element={<Overview />} />
        <Route path="reports" element={<Reports />} />
        <Route path="map" element={<MapView />} />
        <Route path="priority" element={<Priority />} />
        <Route path="cost-estimation" element={<CostEstimation />} />
        <Route path="statistics" element={<Statistics />} />
      </Route>
      <Route path="*" element={<Navigate to="/" />} />
    </Routes>
  );
}
