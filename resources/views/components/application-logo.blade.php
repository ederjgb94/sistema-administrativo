<!-- Logo estilo Apple TV oficial con gradiente equilibrado -->
<svg viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg" {{ $attributes }}>
    <defs>
        <linearGradient id="appleBlackGradient" x1="0%" y1="0%" x2="100%" y2="100%">
            <stop offset="0%" style="stop-color:#4b5563;stop-opacity:1" />
            <stop offset="40%" style="stop-color:#374151;stop-opacity:1" />
            <stop offset="75%" style="stop-color:#1f2937;stop-opacity:1" />
            <stop offset="100%" style="stop-color:#111827;stop-opacity:1" />
        </linearGradient>
        <filter id="appleGlow" x="-50%" y="-50%" width="200%" height="200%">
            <feDropShadow dx="0" dy="2" stdDeviation="3" flood-opacity="0.25"/>
        </filter>
    </defs>
    
    <!-- Recuadro con gradiente negro equilibrado como Apple TV -->
    <rect x="4" y="4" width="32" height="32" 
          fill="url(#appleBlackGradient)" 
          rx="7" 
          filter="url(#appleGlow)"/>
    
    <!-- Highlight sutil en el borde superior -->
    <rect x="4" y="4" width="32" height="14" 
          fill="url(#appleBlackGradient)" 
          rx="7" 
          opacity="0.25"/>
    
    <!-- Borde muy sutil -->
    <rect x="4" y="4" width="32" height="32" 
          fill="none" 
          rx="7" 
          stroke="#4b5563" 
          stroke-width="0.3" 
          opacity="0.4"/>
    
    <!-- Símbolo de dólar estilo Apple TV -->
    <text x="20" y="25" 
          text-anchor="middle" 
          font-family="-apple-system, BlinkMacSystemFont, 'SF Pro Display', system-ui, sans-serif" 
          font-size="16" 
          font-weight="500" 
          fill="#ffffff" 
          opacity="0.95">$</text>
</svg>
