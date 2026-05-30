import { useQuery } from '@tanstack/react-query';
import { Spin } from 'antd';
import { motion } from 'framer-motion';
import { GoogleMap, useJsApiLoader, HeatmapLayer } from '@react-google-maps/api';
import { getHeatmapData } from '../api';
import { useMemo } from 'react';

const MAP_CENTER = { lat: -7.115, lng: 112.417 };
const LIBRARIES = ['visualization'];

export default function MapView() {
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
