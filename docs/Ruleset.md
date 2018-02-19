**Note** In *italic* are the rules that are not implemented yet. Feel free to submit a PR if you implement any of them.

# Functions

Code | Level | Rule
-----|-------|-------
V001 | MAJOR | Function removed
V002 | MAJOR | Function parameter added
V003 | MINOR | Function added
V004 | PATCH | Function implementation changed
V067 | PATCH | Function parameter name changed
V068 | MAJOR | Function parameter removed
V069 | MAJOR | Function parameter typing added
V070 | MINOR | Function parameter typing removed
V071 | MINOR | Function parameter default added
V072 | MAJOR | Function parameter default removed
V073 | MINOR | Function parameter default value changed

# Classes

Code | Level | Rule
-----|-------|-------
V005 | MAJOR | Class removed
V006 | MAJOR | Class public method removed
V007 | MAJOR | Class protected method removed
V008 | MAJOR | Class public property removed
V009 | MAJOR | Class protected property removed
V010 | MAJOR | Class public method parameter added
V011 | MAJOR | Class protected method parameter added
V012 | MAJOR | *New public constructor (does not match supertype)*
V013 | MAJOR | *New protected constructor (does not match supertype)*
V015 | MAJOR | Class public method added
V016 | MAJOR | Class protected method added
V019 | MAJOR | Class public property added
V020 | MAJOR | Class protected property added
V014 | MINOR | Class added
V017 | MINOR | *Final class public method added*
V018 | MINOR | *Final class protected method added*
V021 | MINOR | *Final class protected method parameter added*
V022 | PATCH | *Final class protected method removed*
V023 | PATCH | [Final] Class public class method implementation changed
V024 | PATCH | [Final] Class protected class method implementation changed
V025 | PATCH | [Final] Class private class method implementation changed
V026 | PATCH | Class private property added
V027 | PATCH | Class private property removed
V028 | PATCH | Class private method added
V029 | PATCH | Class private method removed
V030 | PATCH | *Final class protected method added*
V031 | PATCH | Class private method parameter added
V060 | PATCH | Class public method parameter name changed
V061 | PATCH | Class protected method parameter name changed
V062 | PATCH | Class private method parameter name changed
V080 | ----- | *Final class public method parameter added*
V081 | ----- | *Final class private method parameter added*
V082 | MAJOR | Class public method parameter removed
V083 | MAJOR | Class protected method parameter removed
V084 | PATCH | Class private method parameter removed
V085 | MAJOR | Class public method parameter typing added
V086 | MAJOR | Class protected method parameter typing added
V087 | PATCH | Class private method parameter typing added
V088 | MAJOR | Class public method parameter typing removed
V089 | MAJOR | Class protected method parameter typing removed
V090 | PATCH | Class private method parameter typing removed
V091 | MINOR | Class public method parameter default added
V092 | MINOR | Class protected method parameter default added
V093 | PATCH | Class private method parameter default added
V094 | MAJOR | Class public method parameter default removed
V095 | MAJOR | Class protected method parameter default removed
V096 | PATCH | Class private method parameter default removed
V097 | MAJOR | Class public method parameter default value changed
V098 | MAJOR | Class protected method parameter default value changed
V099 | PATCH | Class private method parameter default value changed
V150 | PATCH | Class public method renamed (case only)
V156 | PATCH | Class protected method renamed (case only)
V157 | PATCH | Class private method renamed (case only)
V154 | PATCH | Class renamed (case only)
VXXX | MAJOR | *Final class public method parameter added*
VXXX | MAJOR | *Final class protected method parameter added*
VXXX | PATCH | *Final class private method parameter added*
VXXX | MAJOR | *Final class public method parameter removed*
VXXX | MAJOR | *Final class protected method parameter removed*
VXXX | PATCH | *Final class private method parameter removed*
VXXX | MAJOR | *Final class public method parameter typing added*
VXXX | MAJOR | *Final class protected method parameter typing added*
VXXX | PATCH | *Final class private method parameter typing added*
VXXX | ????? | *Final class public method parameter typing removed*
VXXX | ????? | *Final class protected method parameter typing removed*
VXXX | PATCH | *Final class private method parameter typing removed*
VXXX | ????? | *Final class public method parameter default added*
VXXX | ????? | *Final class protected method parameter default added*
VXXX | PATCH | *Final class private method parameter default added*
VXXX | ????? | *Final class public method parameter default removed*
VXXX | ????? | *Final class protected method parameter default removed*
VXXX | PATCH | *Final class private method parameter default removed*
VXXX | ????? | *Final class public method parameter default value changed*
VXXX | ????? | *Final class protected method parameter default value changed*
VXXX | PATCH | *Final class private method parameter default value changed*


# Interface

Code | Level | Rule
-----|-------|-------
V032 | MINOR | Interface added
V033 | MAJOR | Interface removed
V034 | MAJOR | Interface method added
V035 | MAJOR | Interface method removed
V036 | MAJOR | Interface method parameter added
V063 | PATCH | Interface method parameter name changed
V074 | MAJOR | Interface method parameter removed
V075 | MAJOR | Interface method parameter typing added
V076 | MAJOR | Interface method parameter typing removed
V077 | MINOR | Interface method parameter default added
V078 | MAJOR | Interface method parameter default removed
V079 | MAJOR | Interface method parameter default value changed
V151 | PATCH | Interface method renamed (case only)
V153 | PATCH | Interface renamed (case only)

# Trait

Code | Level | Rule
-----|-------|-------
V037 | MAJOR | Trait removed
V038 | MAJOR | Trait public method removed
V039 | MAJOR | Trait protected method removed
V040 | MAJOR | Trait public property removed
V041 | MAJOR | Trait protected property removed
V042 | MAJOR | Trait public method parameter added
V043 | MAJOR | Trait protected method parameter added
V044 | MAJOR | *New public constructor (does not match supertype)*
V045 | MAJOR | *New protected constructor (does not match supertype)*
V047 | MAJOR | Trait public method added
V048 | MAJOR | Trait protected method added
V049 | MAJOR | Trait public property added
V050 | MAJOR | Trait protected property added
V055 | MAJOR | Trait private property added
V056 | MAJOR | Trait private property removed
V057 | MAJOR | Trait private method added
V058 | MAJOR | Trait private method removed
V046 | MINOR | Trait added
V051 | ----- | *REMOVED*
V052 | PATCH | Trait public method implementation changed
V053 | PATCH | Trait protected method implementation changed
V054 | PATCH | Trait private method implementation changed
V059 | PATCH | Trait private method parameter added
V064 | PATCH | Trait public method parameter name changed
V065 | PATCH | Trait protected method parameter name changed
V066 | PATCH | Trait private method parameter name changed
V100 | MAJOR | Trait public method parameter removed
V101 | MAJOR | Trait protected method parameter removed
V102 | MAJOR | Trait private method parameter removed
V103 | MAJOR | Trait public method parameter typing added
V104 | MAJOR | Trait protected method parameter typing added
V105 | MAJOR | Trait private method parameter typing added
V106 | MAJOR | Trait public method parameter typing removed
V107 | MAJOR | Trait protected method parameter typing removed
V108 | MAJOR | Trait private method parameter typing removed
V109 | MINOR | Trait public method parameter default added
V110 | MINOR | Trait protected method parameter default added
V111 | MINOR | Trait private method parameter default added
V112 | MAJOR | Trait public method parameter default removed
V113 | MAJOR | Trait protected method parameter default removed
V114 | MAJOR | Trait private method parameter default removed
V115 | MAJOR | Trait public method parameter default value changed
V116 | MAJOR | Trait protected method parameter default value changed
V117 | MAJOR | Trait private method parameter default value changed
V152 | PATCH | Trait public method renamed (case only)
V158 | PATCH | Trait protected method renamed (case only)
V159 | PATCH | Trait private method renamed (case only)
V155 | PATCH | Trait renamed (case only)

# To classify

* Method visibility changed
