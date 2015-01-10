1. Scan files specified by the source
2. Extract classes and functions and store them in a registry
3. Scan files specified by the target
4. Extract classes and functions and store them in a second registry
5. Compare registries.
	1. Fast mode: Figure out what needs to change, from PATCH -> MINOR -> MAJOR. As soon as MAJOR needs to be incremented, print out the first reason why.
	2. Exhaustive mode: For every mismatch, print what its impact is (MAJOR, MINOR, PATCH) and why. List the reasons in priority order (MAJOR, MINOR, PATCH).