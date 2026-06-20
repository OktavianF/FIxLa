import { useQuery } from '@tanstack/react-query';
import { Spin } from 'antd';
import { motion } from 'framer-motion';
import { GoogleMap, useJsApiLoader, HeatmapLayer, Marker, InfoWindow } from '@react-google-maps/api';
import { getHeatmapData } from '../api';
import { useMemo, useState } from 'react';
import { useNavigate } from 'react-router-dom';

const MAP_CENTER = { lat: -7.115, lng: 112.417 };
const LIBRARIES = ['visualization'];

export default function MapView() {
  const navigate = useNavigate();
  const [selectedReport, setSelectedReport] = useState(null);

  const { isLoaded } = useJsApiLoader({
    googleMapsApiKey: 'AIzaSyBymY0_zkvlwMzHDRID7oSAwnUXB9I9eTA',
    libraries: LIBRARIES,
    version: '3.64',
  });

  const { data, isLoading } = useQuery({
    queryKey: ['heatmap'],
    queryFn: () => getHeatmapData().then((r) => r.data.data),
  });

  const heatmapData = useMemo(() => {
    if (!data || !window.google) return [];
    return data.map((r) => ({
      ...r,
      location: new window.google.maps.LatLng(parseFloat(r.latitude), parseFloat(r.longitude)),
      weight: parseFloat(r.priority_score) / 10,
    }));
  }, [data]);

  if (!isLoaded || isLoading) {
    return <Spin size="large" style={{ display: 'block', margin: '100px auto' }} />;
  }

  return (
    <div>
      <div className="page-header">
        <div>
          <h1 style={{ marginBottom: 6 }}>Peta Kerusakan Jalan</h1>
          <p style={{ fontSize: 15, color: '#94A3B8', fontWeight: 500 }}>Visualisasi heatmap berdasarkan lokasi dan skor prioritas.</p>
        </div>
      </div>

      <motion.div 
        initial={{ opacity: 0, scale: 0.98 }}
        animate={{ opacity: 1, scale: 1 }}
        transition={{ duration: 0.5 }}
        style={{ borderRadius: 24, overflow: 'hidden', boxShadow: '0 10px 40px rgba(0,0,0,0.08)', border: '1px solid rgba(0,0,0,0.04)' }}
      >
        <GoogleMap
          mapContainerStyle={{ width: '100%', height: 'calc(100vh - 200px)' }}
          center={MAP_CENTER}
          zoom={13}
          options={{
            styles: [
              { featureType: 'poi', elementType: 'labels', stylers: [{ visibility: 'off' }] },
              { elementType: 'labels.text.fill', stylers: [{ color: '#64748B' }] },
              { featureType: 'road', elementType: 'geometry', stylers: [{ color: '#E2E8F0' }] },
              { featureType: 'water', elementType: 'geometry', stylers: [{ color: '#DBEAFE' }] },
            ],
            disableDefaultUI: true,
            zoomControl: true,
          }}
        >
          {heatmapData.length > 0 && (
            <HeatmapLayer
              data={heatmapData}
              options={{
                radius: 30,
                opacity: 0.7,
                gradient: [
                  'rgba(0, 255, 0, 0)',
                  'rgba(16, 185, 129, 1)',
                  'rgba(245, 158, 11, 1)',
                  'rgba(249, 115, 22, 1)',
                  'rgba(239, 68, 68, 1)',
                ],
              }}
            />
          )}

          {heatmapData.map((report) => {
            const damage = report.damage_level?.toLowerCase() || 'ringan';
            let color = '#10B981'; // ringan
            if (damage === 'sedang') color = '#F59E0B';
            if (damage === 'berat') color = '#EF4444';

            return (
              <Marker
                key={report.id}
                position={report.location}
                onClick={() => setSelectedReport(report)}
                icon={{
                  path: window.google.maps.SymbolPath.CIRCLE,
                  scale: 6,
                  fillColor: color,
                  fillOpacity: 1,
                  strokeColor: '#ffffff',
                  strokeWeight: 2,
                }}
              />
            );
          })}

          {selectedReport && (
            <InfoWindow
              position={selectedReport.location}
              onCloseClick={() => setSelectedReport(null)}
              options={{ maxWidth: 300 }}
            >
              <div style={{ padding: 8, fontFamily: 'Inter, sans-serif' }}>
                {selectedReport.photos && selectedReport.photos.length > 0 && (
                  <img 
                    src={selectedReport.photos[0].url} 
                    alt="Laporan" 
                    style={{ width: '100%', height: 120, objectFit: 'cover', borderRadius: 8, marginBottom: 12 }} 
                  />
                )}
                <h4 style={{ margin: '0 0 4px', fontSize: 14, fontWeight: 700, color: '#0F172A' }}>
                  {selectedReport.address || 'Lokasi tidak diketahui'}
                </h4>
                <div style={{ display: 'flex', gap: 6, marginBottom: 8 }}>
                  <span style={{ padding: '2px 8px', borderRadius: 6, fontSize: 10, fontWeight: 700, backgroundColor: '#E2E8F0', color: '#475569', textTransform: 'uppercase' }}>
                    {selectedReport.damage_level || 'N/A'}
                  </span>
                  <span style={{ padding: '2px 8px', borderRadius: 6, fontSize: 10, fontWeight: 700, backgroundColor: '#E2E8F0', color: '#475569', textTransform: 'uppercase' }}>
                    {selectedReport.status || 'pending'}
                  </span>
                </div>
                <p style={{ fontSize: 12, color: '#64748B', margin: '0 0 12px', display: '-webkit-box', WebkitLineClamp: 2, WebkitBoxOrient: 'vertical', overflow: 'hidden' }}>
                  {selectedReport.description || 'Tidak ada deskripsi'}
                </p>
                <button
                  onClick={() => navigate(`/reports?search=${selectedReport.id}`)}
                  style={{ width: '100%', padding: '8px 0', border: 'none', borderRadius: 6, backgroundColor: '#EFF6FF', color: '#2563EB', fontWeight: 600, fontSize: 12, cursor: 'pointer', transition: 'all 0.2s' }}
                  onMouseOver={(e) => { e.currentTarget.style.backgroundColor = '#DBEAFE'; }}
                  onMouseOut={(e) => { e.currentTarget.style.backgroundColor = '#EFF6FF'; }}
                >
                  Lihat Detail
                </button>
              </div>
            </InfoWindow>
          )}
        </GoogleMap>
      </motion.div>

      <motion.div 
        initial={{ opacity: 0, y: 10 }}
        animate={{ opacity: 1, y: 0 }}
        transition={{ delay: 0.3 }}
        style={{ display: 'flex', gap: 24, marginTop: 16, justifyContent: 'center', alignItems: 'center' }}
      >
        <span style={{ fontSize: 13, color: '#94A3B8', fontWeight: 600 }}>Legenda:</span>
        {[
          { color: '#10B981', label: 'Ringan' },
          { color: '#F59E0B', label: 'Sedang' },
          { color: '#EF4444', label: 'Berat' },
        ].map((l) => (
          <div key={l.label} style={{ display: 'flex', alignItems: 'center', gap: 8 }}>
            <div style={{ width: 12, height: 12, borderRadius: 4, background: l.color, boxShadow: `0 2px 6px ${l.color}40` }} />
            <span style={{ fontSize: 13, color: '#475569', fontWeight: 600 }}>{l.label}</span>
          </div>
        ))}
      </motion.div>
    </div>
  );
}
