# Design and Implementation of a Trustworthy Hiking Route Recommendation System with GPX Dataset Provenance Using Blockchain and IPFS

## SINTA 3 Optimized Research Project

### Updated Improvements
- Updated title emphasizing Trustworthy Recommendation
- Added Research Questions
- Added Research Contribution Statement
- Added Research Gap Analysis
- Added Expected Outputs
- Added Success Criteria
- Added Dataset Acquisition Strategy
- Added Provenance History Module
- Added Version Recovery Evaluation
- Added Reviewer-Oriented Positioning
- Optimized scope for JAIC / SINTA 3

---

# Research Background

Existing hiking route recommendation systems focus primarily on recommendation relevance through recommendation algorithms. However, the trustworthiness and traceability of GPX datasets remain underexplored.

GPX files are often collected from various sources and may be modified without clear documentation, resulting in uncertain provenance, version history, and dataset integrity.

This research proposes a trustworthy hiking route recommendation system by integrating:

- Sentence-BERT (SBERT)
- Cosine Similarity
- GPX Dataset Provenance
- Blockchain Metadata Registry
- IPFS Storage
- Version Control
- Integrity Verification

The goal is not merely to generate recommendations but to ensure that recommended routes are supported by verifiable and traceable GPX datasets.

---

# Research Contributions

1. Develop a hiking route recommendation system using SBERT and Cosine Similarity.
2. Integrate GPX dataset provenance into recommendation workflows.
3. Provide GPX dataset version control.
4. Implement dataset integrity verification using Blockchain and IPFS.
5. Improve trustworthiness of route recommendation systems.

---

# Research Questions

RQ1:
How can SBERT-based recommendation provide relevant hiking route recommendations?

RQ2:
How can Blockchain and IPFS support GPX dataset provenance?

RQ3:
How effective is the proposed version control and integrity verification mechanism?

RQ4:
How do users perceive the usefulness and usability of the proposed system?

---

# Research Gap

| Research Area | Recommendation | GPX | Blockchain | IPFS | Provenance |
|--------------|---------------|------|------------|------|------------|
| Hiking Recommendation Studies | Yes | Limited | No | No | No |
| Blockchain Document Systems | No | No | Yes | Sometimes | No |
| IPFS Storage Systems | No | No | Sometimes | Yes | No |
| Proposed Research | Yes | Yes | Yes | Yes | Yes |

---

# Novelty Statement

Unlike previous hiking route recommendation studies that focus solely on recommendation relevance, this research introduces GPX dataset provenance, version control, and integrity verification mechanisms using Blockchain and IPFS.

The resulting system provides both recommendation relevance and dataset trustworthiness.

---

# System Overview

Core Concept:

SBERT
+
Cosine Similarity
+
GPX Dataset Management
+
Version Control
+
Blockchain Provenance
+
IPFS Storage
=
Trustworthy Hiking Route Recommendation

---

# Dataset Acquisition

Dataset sources:

- Public GPX repositories
- Hiking communities
- OpenStreetMap derived routes
- Personal GPS tracking data
- Public hiking datasets

Pipeline:

Collect
↓
Clean
↓
Validate
↓
Store GPX
↓
Generate Hash
↓
Store in IPFS
↓
Register IPFS Metadata
↓
Register Blockchain Metadata
↓
Recommendation Process

---

# Dataset Target

Target implementation:

- Minimum 20 mountains
- 50–100 hiking routes
- 50–100 GPX files
- Multiple versions for selected routes

Example:

Merbabu Selo v1
Merbabu Selo v2

Lawu Cemoro Sewu v1
Lawu Cemoro Sewu v2

Used for provenance and version recovery testing.

---

# Architecture

User
↓
Preference Input
↓
SBERT Embedding
↓
Cosine Similarity
↓
Route Ranking
↓
Recommendation Results
↓
GPX Verification
↓
Blockchain Metadata + IPFS Storage

---

# Main Modules

## Recommendation Module

- SBERT
- Cosine Similarity
- Top-N Recommendation

## GPX Dataset Module

- Upload GPX
- Version Control
- Dataset Management

## Provenance Module

- Hash Generation
- CID Tracking
- Metadata Registry

## Verification Module

- Integrity Verification
- Provenance History

---

# Provenance Model

GPX Dataset
↓
Version Control
↓
Hash Generation
↓
IPFS Upload
↓
CID Generation
↓
Blockchain Registry
↓
Provenance History

Stored Metadata:

- Route ID
- Version
- CID
- File Hash
- Timestamp
- Uploader
- Change Log

IPFS Metadata Strategy:

- The original GPX file is uploaded to IPFS without modifying its content.
- The SHA-256 hash is calculated from the original GPX file used by the recommendation system.
- Route context is attached as Pinata metadata, not embedded into the GPX file.
- This preserves file integrity while improving traceability and dataset discoverability.

Pinata Metadata Fields:

- Route ID
- Route Name
- GPX Version ID
- Version Number
- SHA-256 File Hash
- Verification Status
- Distance (km)
- Elevation Gain (m)
- Naismith Duration (hour)
- Average Grade (%)
- Change Log
- Provenance Created Timestamp

This separation ensures that IPFS stores the verifiable GPX artifact, while Pinata metadata provides human-readable provenance information for route-level audit and paper evaluation.

Blockchain Metadata Registry Scope:

- Blockchain is used only as an immutable metadata registry.
- The smart contract does not store GPX files, route coordinates, recommendation embeddings, comments, ratings, or long textual descriptions.
- Operational data remains in the Laravel/MySQL database.
- The blockchain record stores only the minimum provenance proof required for auditability.

Smart Contract Stored Fields:

- Route ID
- Version Number
- SHA-256 File Hash
- IPFS CID
- Registered By Wallet Address
- Blockchain Timestamp

Smart Contract Functions:

- `registerGpxVersion(routeId, versionNumber, fileHash, ipfsCid)`
- `getGpxVersion(routeId, versionNumber)`
- `isRegistered(routeId, versionNumber)`

This design keeps the blockchain component lightweight and aligned with the research objective: proving that each recommended GPX dataset version has a verifiable hash and IPFS reference.

---

# Provenance History Page

Displayed Information:

- Version Number
- CID
- File Hash
- Timestamp
- Uploader
- Change Log
- Verification Status

Example:

Version 1
↓
Version 2
↓
Version 3
↓
Version 4

---

# Integrity Verification

Verification Process:

1. Download GPX from IPFS.
2. Generate hash.
3. Retrieve blockchain hash.
4. Compare hashes.

Results:

VERIFIED
=
Hashes Match

INVALID
=
Hashes Do Not Match

---

# Why Blockchain Instead of Database?

Blockchain is not used to replace the database.

Blockchain is used specifically for:

- Immutable audit trail
- Version tracking
- Provenance history
- Integrity verification

Operational data remains in the primary database.

---

# Expected Outputs

1. Hiking Route Recommendation Dashboard
2. GPX Dataset Management Module
3. Provenance History Page
4. Integrity Verification Page
5. Recommendation API
6. Version History Dashboard

---

# Success Criteria

Recommendation:

- Precision@5 > 0.75
- Precision@10 > 0.70

Integrity:

- Hash Verification Accuracy = 100%

Version Control:

- Version Recovery Accuracy = 100%

Usability:

- SUS Score > 70

---

# Evaluation Framework

## Functional Testing

Black Box Testing

## Recommendation Quality

- Precision@5
- Precision@10

## Provenance Evaluation

- Version Recovery Accuracy

## Integrity Evaluation

- Hash Verification Accuracy

## IPFS Evaluation

- Upload Time
- Retrieval Time

## User Evaluation

- System Usability Scale (SUS)

---

# Technology Stack

Frontend:
- Laravel Blade

Backend:
- Laravel 12

Recommendation Service:
- Python FastAPI
- Sentence Transformers

Database:
- MySQL

Storage:
- IPFS
- Pinata

Blockchain:
- Ganache
- Sepolia Testnet

---

# Practical Benefits

For Hikers:

- Verified GPX routes
- Trustworthy recommendations
- Clear route history

For Route Managers:

- Dataset version tracking
- Change documentation

For Researchers:

- Provenance-aware datasets
- Better geospatial data management

---

# Future Work

1. Provenance-aware recommendation score.
2. Contributor trust score.
3. Mobile hiking application.
4. Real-time GPS synchronization.
5. Collaborative recommendation.
6. Outdoor tourism platform integration.

---

# Reviewer-Oriented Positioning

Primary Contribution:

Trustworthy Hiking Route Recommendation

Supported By:

- SBERT Recommendation
- GPX Dataset Provenance
- Version Control
- Integrity Verification
- Blockchain Metadata Registry
- IPFS Storage

This positioning aligns strongly with Information Systems journals and is optimized for SINTA 3 publication targets such as JAIC, JournalISI, and related journals.

---

# Conclusion

This research extends an existing SBERT-based hiking recommendation system by introducing dataset provenance, version control, and integrity verification mechanisms.

The novelty lies not in blockchain technology itself, but in improving recommendation trustworthiness through verifiable GPX dataset management.

The scope is realistic, implementable, and aligned with SINTA 3 Information Systems publication standards.
