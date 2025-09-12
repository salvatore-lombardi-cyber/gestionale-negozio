⏺ 📚 DOCUMENTAZIONE GOLDEN STANDARD - "ASPETTO DEI BENI"

  🎨 COLORI E GRADIENTI

  Background principale:
  body {
      background: linear-gradient(135deg, #029D7E 0%, #4DC9A5 100%);
  }

  Container glassmorphism:
  background: rgba(255, 255, 255, 0.95);
  backdrop-filter: blur(10px);
  border-radius: 20px;
  box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
  border: 1px solid rgba(255, 255, 255, 0.2);

  Button gradients:
  /* Primario/Success */
  background: linear-gradient(135deg, #029D7E, #4DC9A5);
  /* Secondario */
  background: linear-gradient(135deg, #6c757d, #545b62);

  📊 STATISTICHE CARDS

  Layout Grid:
  .metrics-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 1rem;
      margin-bottom: 2rem;
  }

  Card Structure:
  .metric-card {
      background: white;
      border-radius: 20px;
      padding: 1.5rem;
      box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
      border: 1px solid rgba(255, 255, 255, 0.2);
      height: 100%;
  }

  Colori cards (in ordine):
  1. linear-gradient(135deg, #4ecdc4, #44a08d) - Turchese-Verde
  2. linear-gradient(135deg, #48cae4, #0077b6) - Azzurro-Blu
  3. linear-gradient(135deg, #9c27b0, #7b1fa2) - Viola-Magenta
  4. linear-gradient(135deg, #ffd60a, #ff8500) - Giallo-Arancione
  5. linear-gradient(135deg, #f72585, #c5025a) - Rosso (se serve)
  6. linear-gradient(135deg, #f8f9fa, #e9ecef) - Madreperla (se serve)

  🔲 BUTTON STANDARD

  Dimensioni:
  .modern-btn {
      padding: 12px 24px;
      border-radius: 12px;
      font-weight: 600;
      font-size: 0.95rem;
      height: ~44px;
  }

  🖼️ MODALE STANDARD

  Struttura:
  .modal-content {
      border-radius: 20px;
      border: none;
  }
  .modal-header {
      background: linear-gradient(135deg, #029D7E, #4DC9A5);
      color: white;
      border-radius: 20px 20px 0 0;
      padding: 1.5rem 2rem;
  }

  📱 RESPONSIVE BREAKPOINTS

  Mobile: @media (max-width: 768px)
  - Padding container: 1rem
  - Hide table, show cards
  - Grid single column

  ✅ STANDARD FEATURES

  1. NO pulsante Esporta
  2. SI statistiche cards (4-6 max)
  3. Modale view dettaglio (non window.open)
  4. Header con "Torna Indietro" + "Nuovo Item"
  5. Search + filtri in sezione separata