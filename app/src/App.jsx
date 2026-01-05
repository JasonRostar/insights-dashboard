export default function App() {
  return (
    <div style={{ padding: 20 }}>
      <header style={{ marginBottom: 16 }}>
        <h2 style={{ margin: 0 }}>Site Insights Dashboard</h2>
        <p style={{ margin: 0, color: "#555" }}>
          React-powered admin dashboard
        </p>
      </header>

      <section
        style={{
          padding: 16,
          border: "1px solid #ddd",
          background: "#fff",
        }}
      >
        <strong>Overview</strong>
        <p style={{ marginTop: 8 }}>
          This is where site data will appear.
        </p>
      </section>
    </div>
  );
}
