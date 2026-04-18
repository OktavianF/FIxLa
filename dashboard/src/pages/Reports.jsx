import { useState } from 'react';
import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import { Table, Tag, Select, Modal, Input, Button, message, Image, Popconfirm } from 'antd';
import { motion } from 'framer-motion';
import { MdVisibility, MdDeleteOutline, MdFilterList } from 'react-icons/md';
import { getReports, updateReportStatus, deleteReport } from '../api';

const STATUS_OPTIONS = [
  { value: 'submitted', label: 'Submitted', color: '#64748B' },
  { value: 'verified', label: 'Verified', color: '#6366F1' },
  { value: 'scheduled', label: 'Scheduled', color: '#F59E0B' },
  { value: 'under_repair', label: 'Under Repair', color: '#7C3AED' },
  { value: 'completed', label: 'Completed', color: '#10B981' },
];

const DAMAGE_COLORS = { ringan: '#10B981', sedang: '#F59E0B', berat: '#EF4444' };

export default function Reports() {
  const [page, setPage] = useState(1);
  const [filters, setFilters] = useState({});
  const [detail, setDetail] = useState(null);
  const [statusModal, setStatusModal] = useState(null);
  const [newStatus, setNewStatus] = useState('');
  const [notes, setNotes] = useState('');
  const queryClient = useQueryClient();

  const { data, isLoading } = useQuery({
    queryKey: ['reports', page, filters],
    queryFn: () => getReports({ page, ...filters }).then((r) => r.data.data),
  });

  const mutation = useMutation({
    mutationFn: ({ id, data }) => updateReportStatus(id, data),
    onSuccess: () => {
      message.success('Status berhasil diperbarui');
      queryClient.invalidateQueries(['reports']);
      setStatusModal(null);
      setNewStatus('');
      setNotes('');
    },
    onError: () => message.error('Gagal memperbarui status'),
  });

  const deleteMutation = useMutation({
    mutationFn: (id) => deleteReport(id),
    onSuccess: () => {
      message.success('Laporan berhasil dihapus');
      queryClient.invalidateQueries(['reports']);
    },
    onError: () => message.error('Gagal menghapus laporan'),
  });

  const columns = [
    { 
      title: 'ID', dataIndex: 'id', width: 70,
      render: (v) => <span style={{ fontWeight: 800, color: '#94A3B8', fontSize: 13 }}>#{v}</span>,
    },
    {
      title: 'Foto',
      dataIndex: 'photos',
      width: 80,
      render: (photos) =>
        photos?.[0] ? (
          <div style={{ borderRadius: 12, overflow: 'hidden', width: 50, height: 50, boxShadow: '0 2px 8px rgba(0,0,0,0.08)' }}>
            <Image src={photos[0].url} width={50} height={50} style={{ objectFit: 'cover' }} />
          </div>
        ) : <div style={{ width: 50, height: 50, borderRadius: 12, background: '#F1F5F9', display: 'flex', alignItems: 'center', justifyContent: 'center', color: '#CBD5E1', fontSize: 20 }}>📷</div>,
    },
    { title: 'Lokasi', dataIndex: 'address', ellipsis: true, render: (v) => <span style={{ fontWeight: 600 }}>{v}</span> },
    { title: 'Kecamatan', dataIndex: 'district', width: 120 },
    {
      title: 'Kerusakan',
      dataIndex: 'damage_level',
      width: 110,
      render: (v) => (
        <span style={{
          padding: '5px 12px',
          borderRadius: 10,
          fontSize: 12,
          fontWeight: 700,
          background: v === 'berat' ? '#FEF2F2' : v === 'sedang' ? '#FFF7ED' : '#ECFDF5',
          color: DAMAGE_COLORS[v],
        }}>{v}</span>
      ),
    },
    {
      title: 'Skor',
      dataIndex: 'priority_score',
      width: 90,
      sorter: (a, b) => a.priority_score - b.priority_score,
      render: (v) => (
        <span style={{
          fontWeight: 900,
          fontSize: 15,
          color: v >= 67 ? '#EF4444' : v >= 34 ? '#F59E0B' : '#10B981',
          fontVariantNumeric: 'tabular-nums',
        }}>{v}</span>
      ),
    },
    {
      title: 'Status',
      dataIndex: 'status',
      width: 130,
      render: (v) => {
        const opt = STATUS_OPTIONS.find((o) => o.value === v);
        return (
          <span style={{
            padding: '5px 12px',
            borderRadius: 10,
            fontSize: 12,
            fontWeight: 700,
            background: v === 'completed' ? '#ECFDF5' : v === 'verified' ? '#EEF2FF' : v === 'under_repair' ? '#FAF5FF' : v === 'scheduled' ? '#FFF7ED' : '#F1F5F9',
            color: opt?.color,
          }}>{opt?.label || v}</span>
        );
      },
    },
    {
      title: 'Tanggal',
      dataIndex: 'created_at',
      width: 110,
      render: (v) => <span style={{ color: '#94A3B8', fontSize: 13, fontWeight: 500 }}>{new Date(v).toLocaleDateString('id-ID')}</span>,
    },
    {
      title: 'Aksi',
      width: 170,
      render: (_, record) => (
        <div style={{ display: 'flex', gap: 6 }}>
          <Button 
            size="small" 
            icon={<MdVisibility />} 
            onClick={() => setDetail(record)} 
            style={{ borderRadius: 10, display: 'flex', alignItems: 'center' }}
          />
          <Button
            size="small"
            type="primary"
            onClick={() => { setStatusModal(record); setNewStatus(record.status); }}
            style={{ borderRadius: 10, fontWeight: 600 }}
          >
            Update
          </Button>
          <Popconfirm
            title="Hapus Laporan"
            description={`Yakin ingin menghapus laporan #${record.id}?`}
            onConfirm={() => deleteMutation.mutate(record.id)}
            okText="Hapus"
            cancelText="Batal"
            okButtonProps={{ danger: true }}
          >
            <Button size="small" danger icon={<MdDeleteOutline />} style={{ borderRadius: 10, display: 'flex', alignItems: 'center' }} />
          </Popconfirm>
        </div>
      ),
    },
  ];

  return (
    <div>
      <div className="page-header">
        <div>
          <h1 style={{ marginBottom: 6 }}>Laporan Masuk</h1>
          <p style={{ fontSize: 15, color: '#94A3B8', fontWeight: 500 }}>Kelola seluruh laporan kerusakan jalan dari masyarakat.</p>
        </div>
        <div style={{ display: 'flex', gap: 10, alignItems: 'center' }}>
          <div style={{ display: 'flex', alignItems: 'center', gap: 6, color: '#94A3B8', fontSize: 13, fontWeight: 600 }}>
            <MdFilterList style={{ fontSize: 18 }} />
            Filter:
          </div>
          <Select
            placeholder="Status"
            allowClear
            style={{ width: 150 }}
            options={STATUS_OPTIONS}
            onChange={(v) => setFilters((f) => ({ ...f, status: v }))}
          />
          <Select
            placeholder="Kerusakan"
            allowClear
            style={{ width: 130 }}
            options={[
              { value: 'ringan', label: 'Ringan' },
              { value: 'sedang', label: 'Sedang' },
              { value: 'berat', label: 'Berat' },
            ]}
            onChange={(v) => setFilters((f) => ({ ...f, damage_level: v }))}
          />
        </div>
      </div>

      <motion.div 
        initial={{ opacity: 0, y: 16 }}
        animate={{ opacity: 1, y: 0 }}
        transition={{ duration: 0.4 }}
        style={{ background: '#fff', borderRadius: 20, padding: 4, boxShadow: '0 4px 6px -1px rgba(0,0,0,0.04), 0 2px 4px -2px rgba(0,0,0,0.02)' }}
      >
        <Table
          dataSource={data?.data || []}
          columns={columns}
          rowKey="id"
          loading={isLoading}
          pagination={{
            current: data?.current_page || 1,
            total: data?.total || 0,
            pageSize: 20,
            onChange: setPage,
            showSizeChanger: false,
          }}
          size="middle"
        />
      </motion.div>

      {/* Detail Modal */}
      <Modal
        title={`Detail Laporan #${detail?.id}`}
        open={!!detail}
        onCancel={() => setDetail(null)}
        footer={null}
        width={640}
      >
        {detail && (
          <div style={{ paddingTop: 8 }}>
            {detail.photos?.length > 0 && (
              <Image.PreviewGroup>
                <div style={{ display: 'flex', gap: 10, marginBottom: 24 }}>
                  {detail.photos.map((p) => (
                    <div key={p.id} style={{ borderRadius: 16, overflow: 'hidden', boxShadow: '0 4px 12px rgba(0,0,0,0.08)' }}>
                      <Image src={p.url} width={120} height={90} style={{ objectFit: 'cover' }} />
                    </div>
                  ))}
                </div>
              </Image.PreviewGroup>
            )}
            <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: '16px 24px' }}>
              {[
                { label: 'Lokasi', value: detail.address },
                { label: 'Kecamatan', value: detail.district },
                { label: 'Kerusakan', value: <span className={`damage-badge ${detail.damage_level}`}>{detail.damage_level}</span> },
                { label: 'Skor Prioritas', value: <span style={{ fontWeight: 800, color: '#6366F1' }}>{detail.priority_score}</span> },
                { label: 'Pelapor', value: detail.user?.name || '-' },
                { label: 'Tanggal', value: new Date(detail.created_at).toLocaleString('id-ID') },
              ].map((item, i) => (
                <div key={i} style={{ padding: '12px 16px', background: '#F8FAFC', borderRadius: 14 }}>
                  <p style={{ fontSize: 12, color: '#94A3B8', fontWeight: 600, textTransform: 'uppercase', letterSpacing: '0.5px', marginBottom: 4 }}>{item.label}</p>
                  <p style={{ fontSize: 15, fontWeight: 600, color: '#0F172A' }}>{item.value}</p>
                </div>
              ))}
            </div>
            {detail.description && (
              <div style={{ marginTop: 16, padding: '14px 16px', background: '#F8FAFC', borderRadius: 14 }}>
                <p style={{ fontSize: 12, color: '#94A3B8', fontWeight: 600, textTransform: 'uppercase', letterSpacing: '0.5px', marginBottom: 4 }}>Deskripsi</p>
                <p style={{ fontSize: 15, color: '#0F172A', lineHeight: 1.6 }}>{detail.description}</p>
              </div>
            )}
          </div>
        )}
      </Modal>

      {/* Update Status Modal */}
      <Modal
        title="Update Status Laporan"
        open={!!statusModal}
        onCancel={() => setStatusModal(null)}
        onOk={() => mutation.mutate({ id: statusModal.id, data: { status: newStatus, notes } })}
        confirmLoading={mutation.isPending}
      >
        <div style={{ display: 'flex', flexDirection: 'column', gap: 16, paddingTop: 8 }}>
          <div>
            <p style={{ fontSize: 12, color: '#94A3B8', fontWeight: 600, textTransform: 'uppercase', letterSpacing: '0.5px', marginBottom: 8 }}>Status Baru</p>
            <Select
              value={newStatus}
              onChange={setNewStatus}
              options={STATUS_OPTIONS}
              style={{ width: '100%' }}
            />
          </div>
          <div>
            <p style={{ fontSize: 12, color: '#94A3B8', fontWeight: 600, textTransform: 'uppercase', letterSpacing: '0.5px', marginBottom: 8 }}>Catatan</p>
            <Input.TextArea
              value={notes}
              onChange={(e) => setNotes(e.target.value)}
              placeholder="Catatan (opsional)"
              rows={3}
            />
          </div>
        </div>
      </Modal>
    </div>
  );
}
