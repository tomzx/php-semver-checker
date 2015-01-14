**Note** In *italic* are the rules that are not implemented yet. Feel free to submit a PR if you implement any of them.

# Functions

Code | Level | Rule
-----|-------|-------
V001 | MAJOR | Function removed
V002 | MAJOR | Function parameter changed
V003 | MINOR | Function added
V004 | PATCH | Function implementation changed

# Classes

Code | Level | Rule
-----|-------|-------
V005 | MAJOR | Class removed
V006 | MAJOR | Class public method removed
V007 | MAJOR | *Class protected method removed*
V008 | MAJOR | *Class public property removed*
V009 | MAJOR | *Class protected property removed*
V010 | MAJOR | Class public method parameter changed
V011 | MAJOR | *Class protected method parameter changed*
V012 | MAJOR | *New public constructor (does not match supertype)*
V013 | MAJOR | *New protected constructor (does not match supertype)*
V014 | MINOR | Class added
V015 | MINOR | Class public method added *(display a notice that the method may overlap)* (MAJOR when not final)
V016 | MINOR | *Class protected method added* *(display a notice that the method may overlap)* (MAJOR when not final)
V017 | MINOR | *Final class public method added*
V018 | MINOR | *Final class protected method added*
V019 | MINOR | *Class public property added*
V020 | MINOR | *Class protected property added*
V021 | MINOR | Class protected method parameter changed (MAJOR when not final)
V022 | MAJOR | Final class protected method removed
V023 | PATCH | [Final] Class public class method implementation changed
V024 | PATCH | [Final] Class *protected* class method implementation changed
V025 | PATCH | [Final] Class *private* class method implementation changed
V026 | PATCH | *Class private property added*
V027 | PATCH | *Class private property removed*
V028 | PATCH | *Class private method added*
V029 | PATCH | *Class private method removed*
V030 | PATCH | *Final class protected method added*
V031 | PATCH | *Class private method parameter changed*

# Interface

Code | Level | Rule
-----|-------|-------
V032 | MAJOR | Interface added
V033 | MAJOR | Interface removed
V034 | MAJOR | Interface method added
V035 | MAJOR | Interface method removed
V036 | MAJOR | Interface method parameter changed

# Trait

Code | Level | Rule
-----|-------|-------
V037 | MAJOR | Trait removed
V038 | MAJOR | Trait public method removed
V039 | MAJOR | *Trait protected method removed*
V040 | MAJOR | *Trait public property removed*
V041 | MAJOR | *Trait protected property removed*
V042 | MAJOR | Trait public method parameter changed
V043 | MAJOR | *Trait protected method parameter changed*
V044 | MAJOR | *New public constructor (does not match supertype)*
V045 | MAJOR | *New protected constructor (does not match supertype)*
V046 | MINOR | Trait added
V047 | MINOR | Trait public method added *(display a notice that the method may overlap)* (MAJOR when not final)
V048 | MINOR | *Trait protected method added* *(display a notice that the method may overlap)* (MAJOR when not final)*
V049 | MINOR | *Trait public property added*
V050 | MINOR | *Trait protected property added*
V051 | MINOR | *Trait protected method parameter changed (MAJOR when not final)*
V052 | PATCH | Trait public trait method implementation changed
V053 | PATCH | *Trait *protected* trait method implementation changed*
V054 | PATCH | *Trait *private* trait method implementation changed*
V055 | PATCH | *Trait private property added*
V056 | PATCH | *Trait private property removed*
V057 | PATCH | *Trait private method added*
V058 | PATCH | *Trait private method removed*
V059 | PATCH | *Trait private method parameter changed*