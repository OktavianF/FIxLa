import { useState } from 'react';
import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import { Table, Tag, Select, Modal, Input, Button, message, Image } from 'antd';
import { MdVisibility } from 'react-icons/md';
import { getReports, updateReportStatus } from '../api';

const STATUS_OPTIONS = [
  { value: 'submitted', label: 'Submitted', color: '#64748B' },
  { value: 'verified', label: 'Verified', color: '#4F46E5' },
  { value: 'scheduled', label: 'Scheduled', color: '#F59E0B' },
  { value: 'under_repair', label: 'Under Repair', color: '#7E22CE' },
  { value: 'completed', label: 'Completed', color: '#10B981' },
];

const DAMAGE_COLORS = { ringan: '#10B981', sedang: '#F59E0B', berat: '#E11D48' };

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

  const columns = [
    { title: 'ID', dataIndex: 'id', width: 60 },
    {
      title: 'Foto',
      dataIndex: 'photos',
      width: 80,
      render: (photos) =>
        photos?.[0] ? (
          <Image src={photos[0].url} width={50} height={50} style={{ objectFit: 'cover', borderRadius: 6 }} />
        ) : '-',
    },
    { title: 'Lokasi', dataIndex: 'address', ellipsis: true },
    { title: 'Kecamatan', dataIndex: 'district', width: 120 },
    {
      title: 'Kerusakan',
      dataIndex: 'damage_level',
      width: 100,
      render: (v) => <Tag color={DAMAGE_COLORS[v]}>{v}</Tag>,
    },
    {
      title: 'Skor',
      dataIndex: 'priority_score',
      width: 80,
      sorter: (a, b) => a.priority_score - b.priority_score,
      render: (v) => <strong>{v}</strong>,
    },
    {
      title: 'Status',
      dataIndex: 'status',
      width: 130,
      render: (v) => {
        const opt = STATUS_OPTIONS.find((o) => o.value === v);
        return <Tag color={opt?.color}>{opt?.label || v}</Tag>;
      },
    },
    {
      title: 'Tanggal',
      dataIndex: 'created_at',
      width: 110,
      render: (v) => new Date(v).toLocaleDateString('id-ID'),
    },
    {
      title: 'Aksi',
      width: 100,
      render: (_, record) => (
        <div style={{ display: 'flex', gap: 4 }}>
          <Button size="small" icon={<MdVisibility />} onClick={() => setDetail(record)} />
          <Button
            size="small"
            type="primary"
            onClick={() => { setStatusModal(record); setNewStatus(record.status); }}
          >
            Update
          </Button>
        </div>
      ),
    },
  ];

  return (
    <div>
      <div className="page-header">
        <h1>Laporan Masuk</h1>
        <div style={{ display: 'flex', gap: 8 }}>
          <Select
            placeholder="Filter Status"
            allowClear
            style={{ width: 160 }}
            options={STATUS_OPTIONS}
            onChange={(v) => setFilters((f) => ({ ...f, status: v }))}
          />
          <Select
            placeholder="Filter Kerusakan"
            allowClear
            style={{ width: 140 }}
            options={[
              { value: 'ringan', label: 'Ringan' },
              { value: 'sedang', label: 'Sedang' },
              { value: 'berat', label: 'Berat' },
            ]}
            onChange={(v) => setFilters((f) => ({ ...f, damage_level: v }))}
          />
        </div>
      </div>

      <div style={{ background: '#fff', borderRadius: 12, padding: 4, boxShadow: '0 1px 3px rgba(0,0,0,0.08)' }}>
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
      </div>

      {/* Detail Modal */}
      <Modal
        title={`Detail Laporan #${detail?.id}`}
        open={!!detail}
        onCancel={() => setDetail(null)}
        footer={null}
        width={600}
      >
        {detail && (
          <div>
            {detail.photos?.length > 0 && (
              <Image.PreviewGroup>
                <div style={{ display: 'flex', gap: 8, marginBottom: 16 }}>
                  {detail.photos.map((p) => (
                    <Image key={p.id} src={p.url} width={120} height={90} style={{ objectFit: 'cover', borderRadius: 8 }} />
                  ))}
                </div>
              </Image.PreviewGroup>
            )}
            <p><strong>Lokasi:</strong> {detail.address}</p>
            <p><strong>Kecamatan:</strong> {detail.district}</p>
            <p><strong>Kerusakan:</strong> <Tag color={DAMAGE_COLORS[detail.damage_level]}>{detail.damage_level}</Tag></p>
            <p><strong>Skor Prioritas:</strong> {detail.priority_score}</p>
            <p><strong>Deskripsi:</strong> {detail.description || '-'}</p>
            <p><strong>Pelapor:</strong> {detail.user?.name || '-'}</p>
            <p><strong>Tanggal:</strong> {new Date(detail.created_at).toLocaleString('id-ID')}</p>
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
        <div style={{ display: 'flex', flexDirection: 'column', gap: 12 }}>
          <Select
            value={newStatus}
            onChange={setNewStatus}
            options={STATUS_OPTIONS}
            style={{ width: '100%' }}
          />
          <Input.TextArea
            value={notes}
            onChange={(e) => setNotes(e.target.value)}
            placeholder="Catatan (opsional)"
            rows={3}
          />
        </div>
      </Modal>
    </div>
  );
}
