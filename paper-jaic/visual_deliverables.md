# Visual Deliverables for Paper

Dokumen ini menyiapkan bahan visual yang disebutkan PRD. Diagram Mermaid dapat dirender di Markdown viewer yang mendukung Mermaid, lalu diekspor sebagai gambar untuk naskah.

## 1. System Architecture

```mermaid
flowchart LR
    User[User / Admin] --> Laravel[Laravel RuteStrip V2]
    Laravel --> Search[SBERT Search and Cosine Similarity]
    Laravel --> DB[(MySQL Operational Database)]
    Laravel --> Storage[(Local GPX Storage)]
    Laravel --> Pinata[Pinata API]
    Pinata --> IPFS[IPFS Gateway / CID]
    Laravel --> Node[Node.js + Ethers.js Script]
    Node --> Chain[Sepolia / Local Ganache]
    Chain --> Contract[GpxProvenanceRegistry]
    Laravel --> Evidence[Paper Outputs / Evidence Pack]
```

## 2. GPX Provenance Flow

```mermaid
sequenceDiagram
    participant Admin
    participant Laravel
    participant Storage
    participant IPFS
    participant Blockchain
    participant DB

    Admin->>Laravel: Upload GPX route/version
    Laravel->>Storage: Store original GPX file
    Laravel->>Laravel: Compute SHA-256 hash
    Laravel->>DB: Create gpx_versions row
    Laravel->>IPFS: Upload original GPX + Pinata metadata
    IPFS-->>Laravel: Return CID and gateway URL
    Laravel->>DB: Store CID, URL, timing, IPFS log
    Admin->>Laravel: Register blockchain metadata
    Laravel->>Blockchain: routeId, versionNumber, fileHash, ipfsCid
    Blockchain-->>Laravel: tx hash / receipt
    Laravel->>DB: Store tx hash, receipt, blockchain log
```

## 3. Integrity Verification Flow

```mermaid
flowchart TD
    A[Select GPX Version] --> B{Verification Source}
    B -->|Local| C[Read GPX from local storage]
    B -->|IPFS| D[Retrieve GPX by CID]
    C --> E[Compute SHA-256]
    D --> E
    E --> F[Compare with stored file_hash]
    F -->|Match| G[Status: verified]
    F -->|Mismatch| H[Status: invalid]
    F -->|Technical error| I[Status: verification_failed]
    G --> J[Save verification_logs]
    H --> J
    I --> J
```

## 4. Recommended Screenshots

1. `/search` or search results with GPX trust badge.
2. `/routes/{id}/provenance` showing version history, hash, CID, blockchain tx, and download button.
3. `/admin/paper-outputs/provenance` showing before/after table and evaluation tables.
4. `/admin/paper-evidence-pack` showing checklist, latest IPFS evidence, latest blockchain evidence.
5. Sepolia transaction page for the registered GPX metadata transaction.
6. `/admin/paper-readiness` showing readiness score and criteria.

## 5. Figure Captions

1. **System architecture of trustworthy hiking route recommendation with GPX provenance.**
2. **GPX provenance workflow from upload, hashing, IPFS pinning, and blockchain metadata registry.**
3. **Integrity verification workflow using SHA-256 comparison from local storage and IPFS retrieval.**
4. **Recommendation result enriched with GPX trust indicator without changing cosine similarity ranking.**
5. **Paper evidence pack containing IPFS CID, Sepolia transaction hash, verification log, and readiness checklist.**
